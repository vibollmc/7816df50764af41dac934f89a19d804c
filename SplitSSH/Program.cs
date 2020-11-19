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
        private static int LolbotSSHMain => int.TryParse(ConfigurationManager.AppSettings["LolbotSSHMain"], out var number) ? number : 1;
        private static bool Mix2SSHProvice => ConfigurationManager.AppSettings["Mix2SSHProvice"] == "true";
        private static bool Mix2SSHProvice2 => ConfigurationManager.AppSettings["Mix2SSHProvice2"] == "true";
        private static string SshFolderBase => ConfigurationManager.AppSettings["SSHFolderBase"];
        private static string BackupFolder => ConfigurationManager.AppSettings["SSHFolderBackup"];
        private static string BackupFolder2  => ConfigurationManager.AppSettings["SSHFolderBackup2"];
        private static string LinkDownloadSsh => ConfigurationManager.AppSettings["LinkDownloadSSH"];
        private static string LinkDownloadSsh2 => ConfigurationManager.AppSettings["LinkDownloadSSH2"];
        private static string LinkDownloadSsh3 => ConfigurationManager.AppSettings["LinkDownloadSSH3"];
        private static string LinkUpdateSsh => ConfigurationManager.AppSettings["LinkUpdateSSH"];
        private static string SShSplitFolder => ConfigurationManager.AppSettings["SSHSplit"];
        private static int NumberVps => int.TryParse(ConfigurationManager.AppSettings["NumberVPS"], out var number) ? number : 1;

        private static int NumberVps2 => int.TryParse(ConfigurationManager.AppSettings["NumberVPS2"], out var number) ? number : 1;
        private static string LolbotFolderBase => ConfigurationManager.AppSettings["LolbotFolderBase"];

        private static string SqliteFile => $"{LolbotFolderBase}\\config\\proxies.sqlite";

        private static string SshFileDownloaded => $"{SshFolderBase}\\ssh.txt";
        private static string SshFileDownloaded2 => $"{SshFolderBase}\\ssh2.txt";
        private static string SshFileDownloaded3 => $"{SshFolderBase}\\ssh3.txt";
        private static string SshOld => $"{SshFolderBase}\\old.txt";
        private static string ConfigFolder => $"{SshFolderBase}\\config";
        private static string ProxiesFile => $"{SshFolderBase}\\config\\proxies.txt";
        private static string CustomversionFile => $"{SshFolderBase}\\custom-version.txt";

        static void Main(string[] args)
        {
            //var result = BotHelper.GetInfo("41.210.15.164");
            //var result2 = BotHelper.GetInfo("14.169.191.253");

            var ftpHelper = new FtpHelper(AddLog);

            //CopyOldFile();

            DownloadFile();

            var lines = File.ReadAllLines(SshFileDownloaded);
            if (lines.Length < 10)
            {
                if (!File.Exists(SshFileDownloaded2)) return;

                lines = File.ReadAllLines(SshFileDownloaded2);
                if (lines.Length < 10) return;
            }

            SplitSsh();

            ftpHelper.Upload9Hit();

            //lolbot
            if (!Convert.ToBoolean(ConfigurationManager.AppSettings["EnableLolBot"])) return;
            
            MakeList();

            ftpHelper.UploadLolbot();
        }

        static void CopyOldFile()
        {
            AddLog("Copy to old file.");
            if (File.Exists(SshFileDownloaded))
                File.Copy(SshFileDownloaded, SshOld, true);
        }


        static void DownloadFile()
        {
            AddLog("Starting download ssh from bitsocks...");

            if (!Directory.Exists(SshFolderBase)) Directory.CreateDirectory(SshFolderBase);

            var webClient = new WebClient();
            AddLog("Downloading link 1...");
            webClient.DownloadFile(LinkDownloadSsh, SshFileDownloaded);

            
            if (File.Exists(LinkDownloadSsh2)) File.Delete(LinkDownloadSsh2);
            if (!string.IsNullOrWhiteSpace(LinkDownloadSsh2))
            {
                AddLog("Downloading link 2...");
                webClient.DownloadFile(LinkDownloadSsh2, SshFileDownloaded2);
            }

            if (File.Exists(LinkDownloadSsh3)) File.Delete(LinkDownloadSsh3);
            if (!string.IsNullOrWhiteSpace(LinkDownloadSsh3))
            {
                AddLog("Downloading link 3...");
                webClient.DownloadFile(LinkDownloadSsh3, SshFileDownloaded3);
            }

            if (!string.IsNullOrWhiteSpace(BackupFolder))
            {
                if (!Directory.Exists(BackupFolder)) Directory.CreateDirectory(BackupFolder);
                File.Copy(SshFileDownloaded, $"{BackupFolder}\\1_{DateTime.Now:yyyyMMddHHmmss}.txt");

                if (File.Exists(LinkDownloadSsh2))
                {
                    File.Copy(SshFileDownloaded2, $"{BackupFolder}\\2_{DateTime.Now:yyyyMMddHHmmss}.txt");
                }

                if (File.Exists(LinkDownloadSsh3))
                {
                    File.Copy(SshFileDownloaded3, $"{BackupFolder}\\3_{DateTime.Now:yyyyMMddHHmmss}.txt");
                }
            }

            AddLog("Download ssh finined.");
        }

        static void SplitSsh()
        {
            AddLog("Starting split ssh for 9hits...");
            var lines = new List<string>();
            lines.AddRange(File.ReadAllLines(SshFileDownloaded));
            var useFile1 = true;
            if (lines.Count < 10)
            {
                if (!File.Exists(SshFileDownloaded2)) return;
                useFile1 = false;
                lines.AddRange(File.ReadAllLines(SshFileDownloaded2));
                if (lines.Count < 10) return;
            }

            if (Mix2SSHProvice && useFile1 && File.Exists(SshFileDownloaded2))
            {
                var lines2 = File.ReadAllLines(SshFileDownloaded2);
                if (lines2.Length > 10)
                {
                    lines.AddRange(lines2);
                }
            }

            if (Mix2SSHProvice && File.Exists(SshFileDownloaded3))
            {
                var lines3 = File.ReadAllLines(SshFileDownloaded3);
                if (lines3.Length > 10)
                {
                    lines.AddRange(lines3);
                }
            }

            var max = ((int) lines.Count / NumberVps) + 1;

            var currentVersion = Convert.ToInt32(File.ReadAllText(CustomversionFile));

            File.Delete(CustomversionFile);
            File.WriteAllText(CustomversionFile, (currentVersion + 1).ToString());

            for (var i = 0; i < NumberVps; i++)
            {
                var line = lines.Skip(i * max).Take(max).ToArray();

                var zipPath = $"{SshFolderBase}\\{i + 1:000}.zip";
                var checkPath = $"{SshFolderBase}\\{i + 1:000}.txt";
                //var splitFile = $"{_sSHSplitFolder}\\{i + 1:000}.txt";

                if (File.Exists(ProxiesFile)) File.Delete(ProxiesFile);

                File.WriteAllLines(ProxiesFile, line);

                //if (File.Exists(splitFile)) File.Delete(splitFile);
                //File.WriteAllLines(splitFile, line);

                if (File.Exists(zipPath)) File.Delete(zipPath);

                ZipFile.CreateFromDirectory(ConfigFolder, zipPath, CompressionLevel.Optimal, true);

                if (File.Exists(checkPath)) File.Delete(checkPath);

                var checkContent = $"{currentVersion + 1}|{LinkUpdateSsh}/{i + 1:000}.zip";

                File.WriteAllText(checkPath, checkContent);

                AddLog($"9hits bot {i + 1:000} completed.");
            }

            AddLog("Split ssh finished.");
        }

        static void MakeList()
        {
            AddLog("Starting split ssh for lolbot...");

            var sshFile1 = SshFileDownloaded2;
            var sshFile2 = SshFileDownloaded;

            if (LolbotSSHMain != 2)
            {
                sshFile1 = SshFileDownloaded;
                sshFile2 = SshFileDownloaded2;
            }

            var lines = new List<string>();
            var useFile1 = true;

            if (File.Exists(sshFile1)) lines.AddRange(File.ReadAllLines(sshFile1));
            
            if (lines.Count < 10)
            {
                if (!File.Exists(sshFile2)) return;
                useFile1 = false;
                lines.AddRange(File.ReadAllLines(sshFile2));
                if (lines.Count < 10) return;
            }

            if (Mix2SSHProvice2 && useFile1 && File.Exists(sshFile2))
            {
                var lines2 = File.ReadAllLines(sshFile2);
                if (lines2.Length > 10)
                {
                    lines.AddRange(lines2);
                }
            }



            if (lines.Count < 10) return;

            var max = ((int)lines.Count / NumberVps2) + 1;

            for (var i = 0; i < NumberVps2; i++)
            {
                var line = lines.Skip(i * max).Take(max).ToArray();

                var splitFile = $"{SShSplitFolder}\\{i + 1:000}.txt";

                if (File.Exists(splitFile)) File.Delete(splitFile);

                File.WriteAllLines(splitFile, line);
            }

            AddLog("splited ssh for lolbot.");

            AddLog("Making list proxies for lolbot...");
            for (var i = 1; i <= NumberVps2; i++)
            {
                var splitFile = $"{SShSplitFolder}\\{i:000}.txt";

                lines = new List<string>(); 
                lines.AddRange(File.ReadAllLines(splitFile));

                var index = 0;

                if (File.Exists(SqliteFile)) File.Delete(SqliteFile);

                SQLiteConnection.CreateFile(SqliteFile);

                using (var connection = new SQLiteConnection($"URI=file:{SqliteFile}"))
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
                var zipPath = $"{LolbotFolderBase}\\{i:000}.zip";
                if (File.Exists(zipPath)) File.Delete(zipPath);
                var lolConfigFolder = $"{LolbotFolderBase}\\config";
                ZipFile.CreateFromDirectory(lolConfigFolder, zipPath, CompressionLevel.Optimal, true);

                AddLog($"LolBot {i:000} completed.");
            }


            var currentVersion = Convert.ToInt32(File.ReadAllText(CustomversionFile));
            var checkPath = $"{LolbotFolderBase}\\check.txt";
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
