using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.Configuration;
using System.IO;
using System.IO.Compression;

namespace SplitSSH
{
    class Program
    {

        private static string _sshFolderBase => ConfigurationManager.AppSettings["SSHFolderBase"];
        private static string _backupFolder => ConfigurationManager.AppSettings["SSHFolderBackup"];
        private static string _linkDownloadSSH => ConfigurationManager.AppSettings["LinkDownloadSSH"];
        private static string _linkUpdateSSH => ConfigurationManager.AppSettings["LinkUpdateSSH"];
        private static string _sSHSplitFolder => ConfigurationManager.AppSettings["SSHSplit"];
        private static int _numberVPS
        {
            get
            {
                if (int.TryParse(ConfigurationManager.AppSettings["NumberVPS"], out var number))
                    return number;
                else return 1;
            }
        }
        private static string _sshFileDownloaded => $"{_sshFolderBase}\\ssh.txt";
        private static string _sshOld => $"{_sshFolderBase}\\old.txt";
        private static string _configFolder => $"{_sshFolderBase}\\config";
        private static string _proxiesFile => $"{_sshFolderBase}\\config\\proxies.txt";
        private static string _customversionFile => $"{_sshFolderBase}\\custom-version.txt";

        static void Main(string[] args)
        {
            CopyOldFile();

            DownloadFile();

            SplitSSH();
        }

        static void CopyOldFile()
        {
            AddLog("Copy to old file.");
            if (File.Exists(_sshFileDownloaded))
                File.Copy(_sshFileDownloaded, _sshOld, true);
        }


        static void DownloadFile()
        {
            AddLog("Starting download ssh from bitsocks...");

            if (!Directory.Exists(_sshFolderBase)) Directory.CreateDirectory(_sshFolderBase);
            if (!Directory.Exists(_backupFolder)) Directory.CreateDirectory(_backupFolder);

            var webClient = new WebClient();

            webClient.DownloadFile(_linkDownloadSSH, _sshFileDownloaded);

            File.Copy(_sshFileDownloaded, $"{_backupFolder}\\{DateTime.Now:yyyyMMddHHmmss}.txt");
            AddLog("Download ssh finined.");
        }

        static void SplitSSH()
        {
            AddLog("Starting split ssh for bots...");
            var lines = File.ReadAllLines(_sshFileDownloaded);

            var max = ((int)lines.Length / _numberVPS) + 1;

            var currentVersion = Convert.ToInt32(File.ReadAllText(_customversionFile));

            File.Delete(_customversionFile);
            File.WriteAllText(_customversionFile, (currentVersion + 1).ToString());

            for(var i = 0; i < _numberVPS; i++)
            {
                var line = lines.Skip(i * max).Take(max).ToArray();

                var zipPath = $"{_sshFolderBase}\\{i + 1:000}.zip";
                var checkPath = $"{_sshFolderBase}\\{i + 1:000}.txt";

                var splitFile = $"{_sSHSplitFolder}\\{i + 1:000}.txt";
                
                if (File.Exists(_proxiesFile)) File.Delete(_proxiesFile);

                File.WriteAllLines(_proxiesFile, line);

                if (File.Exists(splitFile)) File.Delete(splitFile);
                File.WriteAllLines(splitFile, line);

                if (File.Exists(zipPath)) File.Delete(zipPath);

                ZipFile.CreateFromDirectory(_configFolder, zipPath, CompressionLevel.Optimal, true);

                if (File.Exists(checkPath)) File.Delete(checkPath);

                var checkContent = $"{currentVersion + 1}|{_linkUpdateSSH}/{i + 1:000}.zip";

                File.WriteAllText(checkPath, checkContent);

                AddLog($"Bot {i + 1:000} completed.");
            }

            AddLog("Split ssh finished.");
        }

        static void AddLog(string message)
        {
            Console.WriteLine($"{DateTime.Now:yyyy-MM-dd HH:mm:ss} {message}");
        }
    }
}
