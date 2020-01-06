using System;
using System.Collections.Generic;
using System.Configuration;
using System.IO;
using System.Linq;
using System.Net;
using System.Security.Policy;
using System.Text;
using System.Threading.Tasks;
using FluentFTP;

namespace SplitSSH
{
    public delegate void AddLog(string message);
    public class FtpHelper
    {
        private readonly string _server;
        private readonly string _username;
        private readonly string _password;
        private readonly string _9HitPatch;
        private readonly string _lolbotPatch;
        private readonly string _sshFolderBase;
        private readonly string _lolbotFolderBase;
        private readonly int _numberVPS;
        private readonly int _numberVPS2;

        private readonly AddLog _event;
        public FtpHelper(AddLog addLog)
        {
            _server = ConfigurationManager.AppSettings["FtpServer"];
            _username = ConfigurationManager.AppSettings["FtpUser"];
            _password = ConfigurationManager.AppSettings["FtpPassword"];
            _9HitPatch = ConfigurationManager.AppSettings["Ftp9HitPatch"];
            _lolbotPatch = ConfigurationManager.AppSettings["FtpLolbotPatch"];

            _sshFolderBase = ConfigurationManager.AppSettings["SSHFolderBase"];
            _lolbotFolderBase = ConfigurationManager.AppSettings["LolbotFolderBase"];
            _numberVPS = int.TryParse(ConfigurationManager.AppSettings["NumberVPS"], out var number) ? number : 1;
            _numberVPS2 = int.TryParse(ConfigurationManager.AppSettings["NumberVPS2"], out number) ? number : 1;

            _event = addLog;
        }

        public bool Upload9Hit()
        {
            try
            {
                _event("Starting upload 9hit...");
                var ftpClient = new FtpClient(_server, new NetworkCredential(_username, _password));
                ftpClient.Connect();
                _event("Connected to ftp server.");

                for (var i = 1; i <= _numberVPS; i++)
                {
                    var zipFile = $"{i:000}.zip";
                    var txtFile = $"{i:000}.txt";

                    if (!File.Exists($"{_sshFolderBase}\\{zipFile}")) continue;
                    if (!File.Exists($"{_sshFolderBase}\\{txtFile}")) continue;

                    ftpClient.UploadFile($"{_sshFolderBase}\\{zipFile}", $"/{_9HitPatch}/{zipFile}",
                        FtpExists.Overwrite, true);
                    _event($"Uploaded file {zipFile}");
                    ftpClient.UploadFile($"{_sshFolderBase}\\{txtFile}", $"/{_9HitPatch}/{txtFile}",
                        FtpExists.Overwrite, true);
                    _event($"Uploaded file {txtFile}");
                }

                ftpClient.Disconnect();
                _event("Completed upload 9hit.");
                return true;
            }
            catch (Exception ex)
            {
                _event("Upload 9hit error: " + ex.Message);
                return false;
            }
        }

        public bool UploadLolbot()
        {
            try
            {
                _event("Starting upload lolbot...");
                var ftpClient = new FtpClient(_server, new NetworkCredential(_username, _password));
                ftpClient.Connect();
                _event("Connected to ftp server.");
                for (var i = 1; i <= _numberVPS2; i++)
                {
                    var zipFile = $"{i:000}.zip";

                    if (!File.Exists($"{_sshFolderBase}\\{zipFile}")) continue;

                    ftpClient.UploadFile($"{_lolbotFolderBase}\\{zipFile}", $"/{_lolbotPatch}/{zipFile}",
                        FtpExists.Overwrite, true);
                    _event($"Uploaded file {zipFile}");
                }

                ftpClient.UploadFile($"{_lolbotFolderBase}\\check.txt", $"/{_lolbotPatch}/check.txt",
                    FtpExists.Overwrite, true);
                _event($"Uploaded file check.txt");

                ftpClient.Disconnect();
                _event("Completed upload lolbot.");
                return true;
            }
            catch (Exception ex)
            {
                _event("Upload lolbot error: " + ex.Message);
                return false;
            }
        }
    }
}
