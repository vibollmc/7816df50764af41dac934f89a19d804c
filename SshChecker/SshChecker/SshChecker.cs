using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading;
using System.Windows.Forms;
using Renci.SshNet;

namespace SshChecker
{
    public partial class SshChecker : Form
    {
        private int _numberOfRecordFinished;
        private int _numberOfRecordWorking;
        private int _numberOfRecords;
        private int _maxThread;
        private List<string> _sshFreshs;
        private List<string> _sshFails;
        private List<string> _sshLoad;
        private List<Thread> _listThread;
        private BackgroundWorker _backgroundWorker;
        private List<string> _fileSshSelected;
        private bool _forceStop = false;

        private readonly IList<MakeRangeIp.IpRange> _geoIpCountryList;

        public SshChecker()
        {
            InitializeComponent();

            _sshLoad = new List<string>();
            _fileSshSelected = new List<string>();

            InitControl(true);

            lblSshLoaded.Text = @"0 sshs in queue";

            lblIpRunning.Text = @"Stopped";
            lblChecking.Text =
                lblChecked.Text = null;

            var path = AppDomain.CurrentDomain.BaseDirectory + "\\GeoIPCountryWhois.csv";

            if (!File.Exists(path)) return;

            _geoIpCountryList = new List<MakeRangeIp.IpRange>();

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

                _geoIpCountryList.Add(new MakeRangeIp.IpRange
                {
                    Country = new MakeRangeIp.Country(arr[4].Trim(), countryName.Trim()),
                    IpBegin = new MakeRangeIp.IpFormat(arr[0]),
                    IpEnd = new MakeRangeIp.IpFormat(arr[1])
                });
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            var openFileDialog = new OpenFileDialog {Filter = @"SSH Files|*.txt|All Files|*.*"};
            openFileDialog.ShowDialog();

            if (!openFileDialog.CheckFileExists || string.IsNullOrWhiteSpace(openFileDialog.FileName)) return;

            if (_fileSshSelected.Contains(openFileDialog.FileName))
            {
                MessageBox.Show($"{openFileDialog.SafeFileName} was selected. Please select another file.", @"Load SSH File",
                    MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            _fileSshSelected.Add(openFileDialog.FileName);

            _sshLoad.AddRange(File.ReadAllLines(openFileDialog.FileName));

            _numberOfRecords = _sshLoad.Count;

            lblSshLoaded.Text = $"{_sshLoad.Count} sshs in queue";
        }

        private void InitControl(bool enable)
        {
            btnClear.Enabled = enable;
            btnSaves.Enabled = _sshFreshs != null && _sshFreshs.Count > 0;
            btnBrowseFile.Enabled = enable;
            lblChecking.Text = null;
            lblChecked.Text = null;
            numericUpDown1.Enabled = enable;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (btnBrowseFile.Enabled)
            {
                if (_sshLoad.Count == 0)
                {
                    MessageBox.Show(@"No ssh in queue. Please select ssh to check.", @"Load SSH File", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    return;
                }

                btnRun.Text = @"Stop";
                _forceStop = false;

                _numberOfRecordFinished = 0;
                _numberOfRecordWorking = 0;

                _sshFreshs = new List<string>();
                _sshFails = new List<string>();

                prbRunningStatus.Maximum = _numberOfRecords;
                prbChecked.Maximum = _numberOfRecords;

                _listThread = new List<Thread>();

                InitControl(false);

                lblChecking.Text =
                    $"Running: {_numberOfRecordWorking}/{_numberOfRecords}";
                lblChecked.Text = $"Checked: {_numberOfRecordFinished}/{_numberOfRecords} | Fresh: {_sshFreshs.Count} | Fail: {_sshFails.Count}";

                prbRunningStatus.Value = _numberOfRecordWorking;
                prbChecked.Value = _numberOfRecordFinished;

                _maxThread = _numberOfRecords;

                _backgroundWorker = new BackgroundWorker();
                _backgroundWorker.DoWork += Worker_DoWork;
                _backgroundWorker.RunWorkerCompleted += Worker_RunWorkerCompleted;
                _backgroundWorker.RunWorkerAsync();
            }
            else
            {
                btnRun.Text = @"Stopping...";
                _forceStop = true;
            }
        }

        private void Worker_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            //InitControl(true);
        }

        private void Worker_DoWork(object sender, DoWorkEventArgs e)
        {
            for (var i = 0; i < _maxThread; i++)
            {
                if (_forceStop) break;

                Thread.Sleep(2);
                var thread = new Thread(DoWork);

                //_listThread.Add(thread);

                thread.Start();
            }
        }

        private void DoWork()
        {
            if (_sshLoad.Count > 0 && !_forceStop)
            {
                var line = _sshLoad[_numberOfRecordWorking];
                
                if (_numberOfRecordWorking < _numberOfRecords)
                    _numberOfRecordWorking++;

                Invoke(new MethodInvoker(() =>
                {
                    lblChecking.Text =
                    $"Running: {_numberOfRecordWorking}/{_numberOfRecords}";

                    prbRunningStatus.Value = _numberOfRecordWorking;

                }));

                var arr = line.Split('|');

                if (arr.Length <= 2) return;

                var ip = arr[0];
                var user = arr[1];
                var pass = arr[2];
                    
                Invoke(new MethodInvoker(() =>
                {
                    lblIpRunning.Text = $"{ip} is checking...";
                }));

                if (string.IsNullOrWhiteSpace(ip) || string.IsNullOrWhiteSpace(user) ||
                    string.IsNullOrWhiteSpace(pass)) return;

                using (var sshClient = new SshClient(ip, user, pass))
                {
                    try
                    {
                        sshClient.ConnectionInfo.Timeout = new TimeSpan(0, 0, (int)numericUpDown1.Value);


                        Console.WriteLine($"{ip} is connecting...");

                        sshClient.Connect();

                        if (sshClient.IsConnected)
                        {
                            Console.WriteLine($"{ip} has connected.");

                            var result = sshClient.RunCommand("curl http://icanhazip.com").Result;
                            if (!string.IsNullOrWhiteSpace(result))
                            {
                                var country = "UNKNOWN";

                                var ipFormat = new MakeRangeIp.IpFormat(ip);

                                var local =
                                    _geoIpCountryList.FirstOrDefault(
                                        x => x.IpBegin.Number <= ipFormat.Number && ipFormat.Number <= x.IpEnd.Number);

                                if (local != null) country = $"{local.Country.CountryName} ({local.Country.ISOCode})";

                                _sshFreshs.Add($"{ip}|{user}|{pass}|{country}");
                            }
                            else
                            {
                                _sshFails.Add($"{line}(Cannot get data from url test)");
                            }

                            sshClient.Disconnect();

                            Console.WriteLine($"{ip} was disconected.");
                        }
                        else
                        {
                            Console.WriteLine($"{ip} cannot connect.");
                        }
                    }
                    catch (Exception ex)
                    {
                        Console.WriteLine($"{ip} has failed: {ex.Message}");

                        _sshFails.Add($"{line}({ex.Message})");
                    }

                    _numberOfRecordFinished++;

                    Invoke(new MethodInvoker(() =>
                    {
                        prbChecked.Value = _numberOfRecordFinished;
                        lblChecked.Text = $"Checked: {_numberOfRecordFinished}/{_numberOfRecords} | Fresh: {_sshFreshs.Count} | Fail: {_sshFails.Count}";
                    }));
                }
            }

            if (_forceStop)
            {
                Invoke(new MethodInvoker(() =>
                {
                    btnRun.Text = @"Run Check";
                    lblIpRunning.Text = @"Stopped";
                    prbRunningStatus.Value = prbRunningStatus.Maximum;
                    InitControl(true);
                }));
            }

            if (_numberOfRecordFinished >= _numberOfRecords)
            {
                Invoke(new MethodInvoker(() =>
                {
                    InitControl(true);
                }));
            }
        }

        private void btnSaves_Click(object sender, EventArgs e)
        {
            var saveFileDialog = new SaveFileDialog
            {
                Title = @"Save SSH File",
                Filter = @"SSH Files|*.txt|All Files|*.*"
            };
            saveFileDialog.ShowDialog();

            if (string.IsNullOrWhiteSpace(saveFileDialog.FileName)) return;

            File.WriteAllLines(saveFileDialog.FileName, _sshFreshs, Encoding.UTF8);

            _numberOfRecordFinished = 0;
            _numberOfRecordWorking = 0;
            _sshFreshs = null;
            _sshFails = null;
            _backgroundWorker = null;
            _listThread = null;
            _sshLoad = new List<string>();

            lblSshLoaded.Text = @"0 sshs in queue";
            lblIpRunning.Text = @"Stopped";

            _fileSshSelected = new List<string>();
        }

        private void btnClear_Click(object sender, EventArgs e)
        {
            lblSshLoaded.Text = @"0 sshs in queue";
            _sshLoad = new List<string>();
            _fileSshSelected = new List<string>();
        }

        private void button1_Click_1(object sender, EventArgs e)
        {
            var frm = new MakeRangeIp();

            frm.Show();
        }

        private void SshChecker_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (_listThread == null || !_listThread.Any(x => x.IsAlive)) return;

            MessageBox.Show("Please wait for some thread close.");
            e.Cancel = true;
        }
    }
}
