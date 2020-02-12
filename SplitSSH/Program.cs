using System;
using System.Collections.Generic;
using System.Data.SQLite;
using System.Linq;
using System.Text;
using System.Net;
using System.Configuration;
using System.Data;
using System.IO;
using System.IO.Compression;
using System.Text.RegularExpressions;
using System.Threading;

namespace SplitSSH
{
    class Program
    {

        private static string _sshFolderBase => ConfigurationManager.AppSettings["SSHFolderBase"];
        private static string _backupFolder => ConfigurationManager.AppSettings["SSHFolderBackup"];
        private static string _backupFolder2  => ConfigurationManager.AppSettings["SSHFolderBackup2"];
        private static string _linkDownloadSSH => ConfigurationManager.AppSettings["LinkDownloadSSH"];
        private static string _linkDownloadSSH2 => ConfigurationManager.AppSettings["LinkDownloadSSH2"];
        private static string _linkUpdateSSH => ConfigurationManager.AppSettings["LinkUpdateSSH"];
        private static string _sSHSplitFolder => ConfigurationManager.AppSettings["SSHSplit"];
        private static int _numberVPS => int.TryParse(ConfigurationManager.AppSettings["NumberVPS"], out var number) ? number : 1;

        private static int _numberVPS2 => int.TryParse(ConfigurationManager.AppSettings["NumberVPS2"], out var number) ? number : 1;
        private static string _lolbotFolderBase => ConfigurationManager.AppSettings["LolbotFolderBase"];

        private static string _sqliteFile => $"{_lolbotFolderBase}\\config\\proxies.sqlite";

        private static string _sshFileDownloaded => $"{_sshFolderBase}\\ssh.txt";
        private static string _sshFileDownloaded2 => $"{_sshFolderBase}\\ssh2.txt";
        private static string _sshOld => $"{_sshFolderBase}\\old.txt";
        private static string _configFolder => $"{_sshFolderBase}\\config";
        private static string _proxiesFile => $"{_sshFolderBase}\\config\\proxies.txt";
        private static string _customversionFile => $"{_sshFolderBase}\\custom-version.txt";

        static void Main(string[] args)
        {
            //var result = BotHelper.GetInfo("41.210.15.164");
            //var result2 = BotHelper.GetInfo("14.169.191.253");

            var ftpHelper = new FtpHelper(AddLog);

            CopyOldFile();

            DownloadFile();

            var lines = File.ReadAllLines(_sshFileDownloaded);
            if (lines.Length < 10)
            {
                if (!File.Exists(_sshFileDownloaded2)) return;

                lines = File.ReadAllLines(_sshFileDownloaded2);
                if (lines.Length < 10) return;
            }

            SplitSSH();

            ftpHelper.Upload9Hit();

            //lolbot
            if (!Convert.ToBoolean(ConfigurationManager.AppSettings["EnableLolBot"])) return;
            
            MakeList();

            ftpHelper.UploadLolbot();
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

            var webClient = new WebClient();
            AddLog("Downloading link 1...");
            webClient.DownloadFile(_linkDownloadSSH, _sshFileDownloaded);

            
            if (File.Exists(_linkDownloadSSH2)) File.Delete(_linkDownloadSSH2);
            if (!string.IsNullOrWhiteSpace(_linkDownloadSSH2))
            {
                AddLog("Downloading link 2...");
                webClient.DownloadFile(_linkDownloadSSH2, _sshFileDownloaded2);
            }

            if (!string.IsNullOrWhiteSpace(_backupFolder))
            {
                if (!Directory.Exists(_backupFolder)) Directory.CreateDirectory(_backupFolder);
                File.Copy(_sshFileDownloaded, $"{_backupFolder}\\1_{DateTime.Now:yyyyMMddHHmmss}.txt");

                if (File.Exists(_linkDownloadSSH2))
                {
                    File.Copy(_sshFileDownloaded2, $"{_backupFolder}\\2_{DateTime.Now:yyyyMMddHHmmss}.txt");
                }
            }

            AddLog("Download ssh finined.");
        }

        static void SplitSSH()
        {
            AddLog("Starting split ssh for 9hits...");
            var lines = File.ReadAllLines(_sshFileDownloaded);
            if (lines.Length < 10)
            {
                if (!File.Exists(_sshFileDownloaded2)) return;
                
                lines = File.ReadAllLines(_sshFileDownloaded2);
                if (lines.Length < 10) return;
            }

            var max = ((int) lines.Length / _numberVPS) + 1;

            var currentVersion = Convert.ToInt32(File.ReadAllText(_customversionFile));

            File.Delete(_customversionFile);
            File.WriteAllText(_customversionFile, (currentVersion + 1).ToString());

            for (var i = 0; i < _numberVPS; i++)
            {
                var line = lines.Skip(i * max).Take(max).ToArray();

                var zipPath = $"{_sshFolderBase}\\{i + 1:000}.zip";
                var checkPath = $"{_sshFolderBase}\\{i + 1:000}.txt";
                //var splitFile = $"{_sSHSplitFolder}\\{i + 1:000}.txt";

                if (File.Exists(_proxiesFile)) File.Delete(_proxiesFile);

                File.WriteAllLines(_proxiesFile, line);

                //if (File.Exists(splitFile)) File.Delete(splitFile);
                //File.WriteAllLines(splitFile, line);

                if (File.Exists(zipPath)) File.Delete(zipPath);

                ZipFile.CreateFromDirectory(_configFolder, zipPath, CompressionLevel.Optimal, true);

                if (File.Exists(checkPath)) File.Delete(checkPath);

                var checkContent = $"{currentVersion + 1}|{_linkUpdateSSH}/{i + 1:000}.zip";

                File.WriteAllText(checkPath, checkContent);

                AddLog($"9hits bot {i + 1:000} completed.");
            }

            AddLog("Split ssh finished.");
        }

        static void MakeList()
        {
            AddLog("Starting split ssh for lolbot...");
            string[] lines;
            if (File.Exists(_sshFileDownloaded2))
                lines = File.ReadAllLines(_sshFileDownloaded2);
            else
                lines = File.ReadAllLines(_sshFileDownloaded);

            if (lines.Length < 10) return;

            var max = ((int)lines.Length / _numberVPS2) + 1;

            for (var i = 0; i < _numberVPS2; i++)
            {
                var line = lines.Skip(i * max).Take(max).ToArray();

                var splitFile = $"{_sSHSplitFolder}\\{i + 1:000}.txt";

                if (File.Exists(splitFile)) File.Delete(splitFile);

                File.WriteAllLines(splitFile, line);
            }

            AddLog("splited ssh for lolbot.");

            AddLog("Making list proxies for lolbot...");
            for (var i = 1; i <= _numberVPS2; i++)
            {
                var splitFile = $"{_sSHSplitFolder}\\{i:000}.txt";
                lines = File.ReadAllLines(splitFile);
                var index = 0;

                if (File.Exists(_sqliteFile)) File.Delete(_sqliteFile);

                SQLiteConnection.CreateFile(_sqliteFile);

                using (var connection = new SQLiteConnection($"URI=file:{_sqliteFile}"))
                {
                    connection.Open();

                    using (var command = new SQLiteCommand(connection))
                    {
                        command.CommandType = CommandType.Text;

                        command.CommandText =
                            @"CREATE TABLE proxies(id INTEGER PRIMARY KEY AUTOINCREMENT, server TEXT, user TEXT, password TEXT, type INTEGER, Country TEXT, wzoneid TEXT, ianazone TEXT, offset TEXT, status TEXT, used INTEGER)";
                        command.ExecuteNonQuery();

                        command.CommandText =
                            @"INSERT INTO proxies(server, user, password, type, Country, wzoneid, ianazone, offset, status, used)
                            VALUES(@server, @user, @password, @type, @Country, @wzoneid, @ianazone, @offset, @status, @used)";

                        foreach (var line in lines)
                        {
                            var data = new LolBotData
                            {
                                Used = 0,
                                Status = "?",
                                Type = 1
                            };

                            var arrayText = line.Split('|');

                            if (arrayText.Length < 3) continue;

                            var search =
                                "(?:[0-9]{1,3}\\.){3}[0-9]{1,3}$";

                            var match = Regex.Match(arrayText[0], search);

                            data.Server = match.Value;
                            data.User = arrayText[1];
                            data.Password = arrayText[2];

                            var info = BotHelper.GetInfo(data.Server);
                            if (info == null)
                                continue;

                            data.Country = info.Country;
                            data.WZoneId = info.WZoneid;
                            data.IanaZone = info.Timezone;
                            data.Offset = info.Offset;

                            index++;

                            data.Id = index;

                            //insert to db
                            command.Parameters.AddWithValue("@server", data.Server);
                            command.Parameters.AddWithValue("@user", data.User);
                            command.Parameters.AddWithValue("@password", data.Password);
                            command.Parameters.AddWithValue("@type", data.Type);
                            command.Parameters.AddWithValue("@Country", data.Country);
                            command.Parameters.AddWithValue("@wzoneid", data.WZoneId);
                            command.Parameters.AddWithValue("@ianazone", data.IanaZone);
                            command.Parameters.AddWithValue("@offset", data.Offset);
                            command.Parameters.AddWithValue("@status", data.Status);
                            command.Parameters.AddWithValue("@used", data.Used);

                            command.ExecuteNonQuery();
                        }
                    }

                    connection.Close();
                }

                //Zipping
                var zipPath = $"{_lolbotFolderBase}\\{i:000}.zip";
                if (File.Exists(zipPath)) File.Delete(zipPath);
                var lolConfigFolder = $"{_lolbotFolderBase}\\config";
                ZipFile.CreateFromDirectory(lolConfigFolder, zipPath, CompressionLevel.Optimal, true);

                AddLog($"LolBot {i:000} completed.");
            }


            var currentVersion = Convert.ToInt32(File.ReadAllText(_customversionFile));
            var checkPath = $"{_lolbotFolderBase}\\check.txt";
            if (File.Exists(checkPath)) File.Delete(checkPath);
            File.WriteAllText(checkPath, $"{currentVersion}");
            AddLog($"Changed custom version.");
        }

        //static void CreateSqliteFile(List<LolBotData> listData)
        //{
        //    try
        //    {
        //        if (File.Exists(_sqliteFile)) File.Delete(_sqliteFile);

        //        SQLiteConnection.CreateFile(_sqliteFile);

        //        using (var connection = new SQLiteConnection($"URI=file:{_sqliteFile}"))
        //        {
        //            connection.Open();

        //            using (var command = new SQLiteCommand(connection))
        //            {
        //                command.CommandType = CommandType.Text;

        //                command.CommandText = @"CREATE TABLE proxies(id INTEGER PRIMARY KEY AUTOINCREMENT, server TEXT, user TEXT, password TEXT, type INTEGER, Country TEXT, wzoneid TEXT, ianazone TEXT, offset TEXT, status TEXT, used INTEGER)";
        //                command.ExecuteNonQuery();

        //                command.CommandText =
        //                    @"INSERT INTO proxies(server, user, password, type, Country, wzoneid, ianazone, offset, status, used)
        //                    VALUES(@server, @user, @password, @type, @Country, @wzoneid, @ianazone, @offset, @status, @used)";

        //                foreach (var data in listData)
        //                {
        //                    command.Parameters.AddWithValue("@server", data.Server);
        //                    command.Parameters.AddWithValue("@user", data.User);
        //                    command.Parameters.AddWithValue("@password", data.Password);
        //                    command.Parameters.AddWithValue("@type", data.Type);
        //                    command.Parameters.AddWithValue("@Country", data.Country);
        //                    command.Parameters.AddWithValue("@wzoneid", data.WZoneId);
        //                    command.Parameters.AddWithValue("@ianazone", data.IanaZone);
        //                    command.Parameters.AddWithValue("@offset", data.Offset);
        //                    command.Parameters.AddWithValue("@status", data.Status);
        //                    command.Parameters.AddWithValue("@used", data.Used);

        //                    command.ExecuteNonQuery();
        //                }
        //            }

        //            connection.Close();
        //        }
        //    }
        //    catch (Exception ex)
        //    {
        //        AddLog("Create sqlite error: " + ex.Message);
        //    }
        //}

        static void AddLog(string message)
        {
            Console.WriteLine($"{DateTime.Now:yyyy-MM-dd HH:mm:ss} {message}");
        }
    }
}
