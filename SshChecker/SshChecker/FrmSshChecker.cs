using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Net.Http;
using System.Security.Policy;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using Newtonsoft.Json;
using Renci.SshNet;

namespace SshChecker
{
    public partial class FrmSshChecker : Form
    {
        private int _numberOfRecordFinished;
        private int _numberOfRecordWorking;
        private List<string> _sshFreshs;
        private List<string> _sshFails;
        private List<string> _sshLoad;
        private List<BackgroundWorker> _listBg;
        private List<string> _fileSshSelected;
        private bool _forceStop = false;

        public class IpInfo
        {
            public string ip { get; set; }
            public string hostname { get; set; }
            public string city { get; set; }
            public string region { get; set; }
            public string country { get; set; }
            public string loc { get; set; }
            public string org { get; set; }
            public string postal { get; set; }
            public string phone { get; set; }
        }

        public FrmSshChecker()
        {
            InitializeComponent();

            _sshLoad = new List<string>();
            _fileSshSelected = new List<string>();

            InitControl(true);

            lblRunningStatus.Text = null;

            lblSshLoaded.Text = @"0 sshs in queue";

            lblRunningStatus.Text = @"Stopped";

            cboNumberTheards.SelectedIndex = 9;
        }

        private async Task<IpInfo> GetIpLocation(string ip)
        {
            var url = $"https://ipinfo.io/{ip}/json";

            using (var httpClient = new HttpClient())
            {
                var response = await httpClient.GetStringAsync(url);

                return string.IsNullOrWhiteSpace(response) ? null : JsonConvert.DeserializeObject<IpInfo>(response);
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            var openFileDialog = new OpenFileDialog();
            openFileDialog.Filter = @"SSH Files|*.txt|All Files|*.*";
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

            _numberOfRecordWorking = _sshLoad.Count;

            lblSshLoaded.Text = $"{_sshLoad.Count} sshs in queue";
            lblRunningStatus.Text = @"Stopped";
        }

        private void InitControl(bool enable)
        {
            btnClear.Enabled = enable;
            cboNumberTheards.Enabled = enable;
            btnSaves.Enabled = _sshFreshs != null && _sshFreshs.Count > 0;
            btnBrowseFile.Enabled = enable;
            lblCurrentRunning.Text = null;
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

                _sshFreshs = new List<string>();
                _sshFails = new List<string>();

                prbRunningStatus.Maximum = _numberOfRecordWorking;

                _listBg = new List<BackgroundWorker>();

                InitControl(false);

                lblRunningStatus.Text =
                    $"Running: {_numberOfRecordFinished}/{_numberOfRecordWorking} | Fresh: {_sshFreshs.Count} | Fail: {_sshFails.Count}";

                prbRunningStatus.Value = _numberOfRecordFinished;

                for (var i = 0; i < int.Parse(cboNumberTheards.Text); i++)
                {
                    var worker = new BackgroundWorker();
                    _listBg.Add(worker);
                    worker.DoWork += Worker_DoWork;
                    worker.RunWorkerCompleted += Worker_RunWorkerCompleted;

                    worker.RunWorkerAsync();
                }
            }
            else
            {
                btnRun.Text = @"Stopping...";
                _forceStop = true;
            }
        }

        private void Worker_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            InitControl(true);
        }

        private void Worker_DoWork(object sender, DoWorkEventArgs e)
        {
            TaskHelper.RunSync(() => DoWork());
        }

        private async Task DoWork()
        {
            while (_sshLoad.Count > 0 && !_forceStop)
            {
                var temp = _sshLoad.ToArray().Clone() as string[];

                var line = temp[temp.Length - 1];

                _sshLoad = _sshLoad.Where(x => x != line).ToList();

                var arr = line.Split('|');

                if (arr.Length > 2)
                {
                    var ip = arr[0];
                    var user = arr[1];
                    var pass = arr[2];
                    
                    Invoke(new MethodInvoker(() =>
                    {
                        lblCurrentRunning.Text = $"{ip} is running...";
                    }));

                    if (!(string.IsNullOrWhiteSpace(ip) || string.IsNullOrWhiteSpace(user) ||
                        string.IsNullOrWhiteSpace(pass)))
                    {
                        using (var sshClient = new SshClient(ip, user, pass))
                        {
                            try
                            {
                                sshClient.Connect();

                                var output = sshClient.RunCommand("echo testing");

                                sshClient.Disconnect();

                                Console.WriteLine(output.Result);

                                var iplocal = await GetIpLocation(ip);

                                var country = string.Empty;

                                if (iplocal != null) country = iplocal.country;

                                _sshFreshs.Add($"{ip}|{user}|{pass}|{country}");
                            }
                            catch (Exception ex)
                            {
                                _sshFails.Add($"{line}({ex.Message})");
                            }
                        }
                    }
                }

                if (_numberOfRecordFinished < _numberOfRecordWorking)
                    _numberOfRecordFinished++;

                Invoke(new MethodInvoker(() =>
                {
                    lblRunningStatus.Text = $"Running: {_numberOfRecordFinished}/{_numberOfRecordWorking} | Fresh: {_sshFreshs.Count} | Fail: {_sshFails.Count}";

                    prbRunningStatus.Value = _numberOfRecordFinished;
                    
                }));
            }

            if (_forceStop)
            {
                Invoke(new MethodInvoker(() =>
                {
                    btnRun.Text = @"Run Check";
                    lblRunningStatus.Text = @"Stopped";
                    prbRunningStatus.Value = prbRunningStatus.Maximum;

                }));
            }
        }

        private void btnSaves_Click(object sender, EventArgs e)
        {
            var saveFileDialog = new SaveFileDialog();
            saveFileDialog.Title = @"Save SSH File";
            saveFileDialog.Filter = @"SSH Files|*.txt|All Files|*.*";
            saveFileDialog.ShowDialog();

            if (!string.IsNullOrWhiteSpace(saveFileDialog.FileName))
            {
                File.WriteAllLines(saveFileDialog.FileName, _sshFreshs, Encoding.UTF8);

                _numberOfRecordFinished = 0;
                _numberOfRecordWorking = 0;
                _sshFreshs = null;
                _sshFails = null;
                _listBg = null;
                _sshLoad = new List<string>();

                lblSshLoaded.Text = @"0 sshs in queue";
                lblRunningStatus.Text = @"Stopped";

                _fileSshSelected = new List<string>();
            }
        }

        private void btnClear_Click(object sender, EventArgs e)
        {
            lblSshLoaded.Text = @"0 sshs in queue";
            _sshLoad = new List<string>();
            _fileSshSelected = new List<string>();
        }
    }
}
