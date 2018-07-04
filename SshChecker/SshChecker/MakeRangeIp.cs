using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Security.Policy;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace SshChecker
{
    public partial class MakeRangeIp : Form
    {
        public class IpFormat
        {
            public int Number1 { get; }
            public int Number2 { get; }
            public int Number3 { get; }
            public int Number4 { get; }
            public long Number { get; set; }

            public IpFormat(string ip)
            {
                var numbers = ip.Split('.');
                if (numbers.Length < 4) return;

                Number1 = int.Parse(numbers[0]);
                Number2 = int.Parse(numbers[1]);
                Number3 = int.Parse(numbers[2]);
                Number4 = int.Parse(numbers[3]);

                Number = ToNumber();
            }

            public override string ToString()
            {
                return $"{Number1}.{Number2}.{Number3}.{Number4}";
            }

            private long ToNumber()
            {
                var ipString = $"{Number1:000}{Number2:000}{Number3:000}{Number4:000}";

                return long.Parse(ipString);
            }
            
            public IpFormat GetIpBegin()
            {
                return new IpFormat($"{Number1}.{Number2}.{Number3}.1");
            }

            public IpFormat GetIpEnd()
            {
                return new IpFormat($"{Number1}.{Number2}.{Number3}.254");
            }
        }

        public class IpRange
        {
            public IpFormat IpBegin { get; set; }
            public IpFormat IpEnd { get; set; }
            public Country Country { get; set; }

            public override string ToString()
            {
                return $"{IpBegin}-{IpEnd}";
            }
        }

        public class Country
        {
            public Country(string code, string name)
            {
                ISOCode = code;
                CountryName = name;
            }

            public string ISOCode { get; }
            public string CountryName { get; }
        }

        private class SSH
        {
            public string Ip { get; set; }
            public string User { get; set; }
            public string Password { get; set; }
        }

        private class UserAndPassword
        {
            public string User { get; set; }
            public string Password { get; set; }

            public int Show { get; set; }

            public override string ToString()
            {
                return $"{User}|{Password} - {Show}";
            }
        }

        private class SSHCountry
        {
            public SSH Ssh { get; set; }
            public string SshString { get; set; }
            public Country Country { get; set; }
        }

        private IList<IpFormat> _ipFormats;

        private IList<UserAndPassword> _userAndPasswords;

        private IList<IpRange> _ipRangeResults;

        private IList<IpRange> _geoIpCountryList;

        private IList<SSH> _sshs;

        private IList<SSHCountry> _sshCountries; 

        private StringBuilder _sshDistinct;

        private BackgroundWorker _backgroundWorker;

        private BackgroundWorker _backgroundWorkerBuild;

        private BackgroundWorker _backgroundWorkerDistinct;

        private BackgroundWorker _backgroundWorkerFilterByCountry;

        public MakeRangeIp()
        {
            InitializeComponent();

            lblStatus.Text = null;

            var path = AppDomain.CurrentDomain.BaseDirectory + "\\GeoIPCountryWhois.csv";

            if (File.Exists(path))
            {
                _geoIpCountryList = new List<IpRange>();

                var lines = File.ReadAllLines(path);

                foreach (var line in lines)
                {

                    var arr = line.Split(',');

                    var countryName = arr[5];

                    if (line.Contains("\""))
                    {
                        var temp = line.Split('"');
                        countryName = temp[1].Trim();
                    }

                    _geoIpCountryList.Add(new IpRange
                    {
                        Country = new Country(arr[4].Trim(), countryName.Trim()),
                        IpBegin = new IpFormat(arr[0]),
                        IpEnd = new IpFormat(arr[1])
                    });
                }
            }
        }

        private void btnFile_Click(object sender, EventArgs e)
        {
            var openFileDialog = new OpenFileDialog {Filter = @"SSH Files|*.txt|All Files|*.*"};

            openFileDialog.ShowDialog();

            if (!openFileDialog.CheckFileExists || string.IsNullOrWhiteSpace(openFileDialog.FileName))
                return;

            txtFile.Text = openFileDialog.FileName;
        }

        private void btnFilter_Click(object sender, EventArgs e)
        {
            if (string.IsNullOrWhiteSpace(txtFile.Text)) return;

            _backgroundWorker = new BackgroundWorker();

            _backgroundWorker.DoWork += _backgroundWorker_DoWork;

            _backgroundWorker.RunWorkerCompleted += _backgroundWorker_RunWorkerCompleted;

            _backgroundWorker.RunWorkerAsync();
        }

        private void _backgroundWorker_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            BuidIpRange();

            var stringBuilder = new StringBuilder();

            foreach (var userAndPassword in _userAndPasswords.OrderByDescending(x=> x.Show))
            {
                stringBuilder.AppendLine(userAndPassword.ToString());
            }
            txtPassword.Text = stringBuilder.ToString();
        }


        private void BuidIpRange()
        {

            _backgroundWorkerBuild = new BackgroundWorker();

            _backgroundWorkerBuild.DoWork += _backgroundWorkerBuild_DoWork;

            _backgroundWorkerBuild.RunWorkerCompleted += _backgroundWorkerBuild_RunWorkerCompleted;

            _backgroundWorkerBuild.RunWorkerAsync();

        }

        private void _backgroundWorkerBuild_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            btnFile.Enabled = true;
            btnRun.Enabled = true;
            button1.Enabled = true;
            btnFilter.Enabled = true;
        }

        private void _backgroundWorkerBuild_DoWork(object sender, DoWorkEventArgs e)
        {
            Invoke(new MethodInvoker(() =>
            {
                btnFile.Enabled = false;
                btnRun.Enabled = false;
                button1.Enabled = false;
                btnFilter.Enabled = false;

                progressBar1.Maximum = 255;

                progressBar1.Value = 0;

                lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

            }));

            var countries = new List<Country> {new Country("", "--All--")};
            _sshCountries = null;
            _ipRangeResults = new List<IpRange>();
            var stringBuilder = new StringBuilder();
            for (var i = 1; i < 255; i++)
            {
                var number = i;

                Invoke(new MethodInvoker(() =>
                {
                    progressBar1.Value = number;

                    lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

                }));

                var ips = _ipFormats.Where(x => x.Number1 == number).OrderBy(x => x.Number);

                if (!ips.Any()) continue;

                var min = ips.First();
                var max = ips.Last();

                var iplocal =
                    _geoIpCountryList.FirstOrDefault(
                        x => (x.IpBegin.Number <= min.Number && x.IpEnd.Number >= min.Number)
                        || (x.IpBegin.Number <= max.Number && x.IpEnd.Number >= max.Number));

                var country = "Unknown";
                var isocode = "Unknown";

                if (iplocal != null)
                {
                    country = iplocal.Country.CountryName;
                    isocode = iplocal.Country.ISOCode;

                    if (countries.All(x => x.ISOCode != isocode))
                    {
                        countries.Add(new Country(isocode, country));
                    }
                }

                var iprange = new IpRange
                {
                    IpEnd = max.GetIpEnd(),
                    IpBegin = min.GetIpBegin(),
                    Country = new Country(isocode, country)
                };

                _ipRangeResults.Add(iprange);

                stringBuilder.AppendLine(iprange.ToString());

            }

            countries = countries.OrderBy(x => x.CountryName).ToList();

            countries.Add(new Country("Unknown", "Unknown"));

            Invoke(new MethodInvoker(() =>
            {
                txtIP.Text = stringBuilder.ToString();

                cboCountry.ValueMember = "ISOCode";
                cboCountry.DisplayMember = "CountryName";

                cboCountry.DataSource = countries;


                progressBar1.Value = 255;

                lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

            }));
            
        }

        private void _backgroundWorker_DoWork(object sender, DoWorkEventArgs e)
        {
            var sshList = File.ReadAllLines(txtFile.Text);

            Invoke(new MethodInvoker(() =>
            {
                btnFile.Enabled = false;
                btnRun.Enabled = false;
                btnFilter.Enabled = false;
                button1.Enabled = false;

                progressBar1.Maximum = sshList.Length;

                progressBar1.Value = 0;

                lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

            }));

            _ipFormats = new List<IpFormat>();

            _userAndPasswords = new List<UserAndPassword>();

            

            foreach (var ssh in sshList)
            {
                Invoke(new MethodInvoker(() =>
                {
                    progressBar1.Value++;

                    lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

                }));

                if (string.IsNullOrWhiteSpace(ssh)) continue;

                var sshDetails = ssh.Split('|');

                if (sshDetails.Length < 3) continue;

                var ip = sshDetails[0];
                var user = sshDetails[1];
                var pass = sshDetails[2];

                var exists = _userAndPasswords.FirstOrDefault(x => x.User == user && x.Password == pass);

                if (!string.IsNullOrWhiteSpace(user) && !string.IsNullOrWhiteSpace(pass) &&
                    exists == null)
                {
                    var userAndPass = new UserAndPassword
                    {
                        User = user,
                        Password = pass,
                        Show = 1
                    };
                    _userAndPasswords.Add(userAndPass);
                }
                else if (exists != null)
                {
                    exists.Show++;
                }

                _ipFormats.Add(new IpFormat(ip));
                
            }
        }

        private void txtFilter_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar == 13)
            {
                BuidIpRange();
            }
        }

        private void cboCountry_SelectedIndexChanged(object sender, EventArgs e)
        {
            var stringBuilder = new StringBuilder();

            if (_ipRangeResults != null)
            {

                foreach (
                    var ipRange in
                        _ipRangeResults.Where(x => x.Country.ISOCode == cboCountry.SelectedValue.ToString() ||
                                                   cboCountry.SelectedValue.ToString() == string.Empty))
                {
                    stringBuilder.AppendLine(ipRange.ToString());
                }
            }
            else if (_sshCountries != null)
            {
                foreach (
                    var ssh in
                        _sshCountries.Where(
                            x =>
                                x.Country.ISOCode == cboCountry.SelectedValue.ToString() ||
                                cboCountry.SelectedValue.ToString() == string.Empty))
                {
                    stringBuilder.AppendLine(ssh.SshString);
                }
            }

            txtIP.Text = stringBuilder.ToString();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (string.IsNullOrWhiteSpace(txtFile.Text)) return;

            _backgroundWorkerDistinct = new BackgroundWorker();
            _backgroundWorkerDistinct.DoWork += _backgroundWorkerDistinct_DoWork;
            _backgroundWorkerDistinct.RunWorkerCompleted += _backgroundWorkerDistinct_RunWorkerCompleted;
            _backgroundWorkerDistinct.RunWorkerAsync();
        }

        private void _backgroundWorkerDistinct_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            btnFile.Enabled = true;
            btnRun.Enabled = true;
            button1.Enabled = true;
            btnFilter.Enabled = true;

            progressBar1.Value = progressBar1.Maximum;

            lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

            txtIP.Text = _sshDistinct.ToString();
        }

        private void _backgroundWorkerDistinct_DoWork(object sender, DoWorkEventArgs e)
        {
            var lines = File.ReadAllLines(txtFile.Text);

            Invoke(new MethodInvoker(() =>
            {
                btnFile.Enabled = false;
                btnRun.Enabled = false;
                button1.Enabled = false;
                btnFilter.Enabled = false;

                progressBar1.Maximum = lines.Length;

                progressBar1.Value = 0;

                lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

            }));

            _sshs = new List<SSH>();
            _sshDistinct = new StringBuilder();

            foreach (var line in lines)
            {
                Invoke(new MethodInvoker(() =>
                {
                    progressBar1.Value++;

                    lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

                }));

                var arr = line.Split('|');

                if (arr.Length < 3) continue;

                var ip = arr[0];
                var user = arr[1];
                var pass = arr[2];

                if (string.IsNullOrWhiteSpace(ip) || string.IsNullOrWhiteSpace(user) || string.IsNullOrWhiteSpace(pass)) continue;

                //if (_sshs.Any(x => x.Ip == ip && x.User == user && x.Password == pass)) continue;

                _sshs.Add(new SSH
                {
                    Password = pass,
                    User = user,
                    Ip = ip
                });

                //_sshDistinct.AppendLine(line);
            }

            if (!_sshs.Any()) return;

            var distincts = _sshs.Select(x => $"{x.Ip}|{x.User}|{x.Password}").Distinct().ToArray();

            _sshDistinct.Append(string.Join(Environment.NewLine, distincts));

            //Invoke(new MethodInvoker(() =>
            //{
            //    progressBar1.Maximum = distincts.Count();

            //    progressBar1.Value = 0;

            //    lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

            //}));

            //foreach (var item in distincts)
            //{
            //    Invoke(new MethodInvoker(() =>
            //    {
            //        progressBar1.Value++;

            //        lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

            //    }));

            //    _sshDistinct.AppendLine();
            //}
        }

        private void btnFilter_Click_1(object sender, EventArgs e)
        {
            if (string.IsNullOrWhiteSpace(txtFile.Text)) return;

            _backgroundWorkerFilterByCountry = new BackgroundWorker();
            _backgroundWorkerFilterByCountry.DoWork += _backgroundWorkerFilterByCountry_DoWork;
            _backgroundWorkerFilterByCountry.RunWorkerCompleted += _backgroundWorkerFilterByCountry_RunWorkerCompleted;
            _backgroundWorkerFilterByCountry.RunWorkerAsync();
        }

        private void _backgroundWorkerFilterByCountry_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            btnFile.Enabled = 
            btnRun.Enabled = 
            button1.Enabled = 
            btnFilter.Enabled = true;

            progressBar1.Value = progressBar1.Maximum;

            lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";
        }

        private void _backgroundWorkerFilterByCountry_DoWork(object sender, DoWorkEventArgs e)
        {
            var lines = File.ReadAllLines(txtFile.Text);

            Invoke(new MethodInvoker(() =>
            {
                btnFile.Enabled = false;
                btnRun.Enabled = false;
                button1.Enabled = false;
                btnFilter.Enabled = false;

                progressBar1.Maximum = lines.Length;

                progressBar1.Value = 0;

                lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

            }));

            _ipRangeResults = null;
            _sshCountries = new List<SSHCountry>();
            var countries = new List<Country> { new Country("", "--All--") };
            foreach (var line in lines)
            {
                Invoke(new MethodInvoker(() =>
                {
                    progressBar1.Value++;

                    lblStatus.Text = $"{progressBar1.Value}/{progressBar1.Maximum}";

                }));

                var arr = line.Split('|');

                if (arr.Length < 3) continue;
                
                var ip = arr[0];
                var user = arr[1];
                var pass = arr[2];

                var ipFormat = new IpFormat(ip);

                var iplocal =
                    _geoIpCountryList.FirstOrDefault(
                        x => x.IpBegin.Number <= ipFormat.Number && ipFormat.Number <= x.IpEnd.Number);

                var country = "Unknown";
                var isocode = "Unknown";

                if (iplocal != null)
                {
                    country = iplocal.Country.CountryName;
                    isocode = iplocal.Country.ISOCode;

                    if (countries.All(x => x.ISOCode != isocode))
                    {
                        countries.Add(new Country(isocode, country));
                    }
                }

                _sshCountries.Add(new SSHCountry
                {
                    Country = new Country(isocode, country),
                    Ssh = new SSH
                    {
                        User = user,
                        Password = pass,
                        Ip = ip
                    },
                    SshString = line
                });

            }

            countries = countries.OrderBy(x => x.CountryName).ToList();

            countries.Add(new Country("Unknown", "Unknown"));

            Invoke(new MethodInvoker(() =>
            {
                cboCountry.ValueMember = "ISOCode";
                cboCountry.DisplayMember = "CountryName";

                cboCountry.DataSource = countries;

            }));
        }

        private void button2_Click(object sender, EventArgs e)
        {
            var result = new StringBuilder();

            var date1 = txtDate.Value;
            var date2 = txtDate2.Value;

            while (date1 <= date2)
            {
                for (var i = 0; i < 24; i++)
                {
                    result.AppendLine(
                        $"http://103.99.2.128:6252/SSH_Mini_Tuan_{date1:dd-MM-yyyy} {i:00}.txt");
                }

                date1 = date1.AddDays(1);
            }
            

            txtIP.Text = result.ToString();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            Clipboard.SetText(txtIP.Text);
        }
    }
}
