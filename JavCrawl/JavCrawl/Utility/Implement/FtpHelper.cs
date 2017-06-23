using JavCrawl.Utility.Context;
using JavCrawl.Dal;
using JavCrawl.Models;
using FluentFTP;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Threading.Tasks;
using System.Net.Http;
using Microsoft.AspNetCore.Hosting;
using System.Net;

namespace JavCrawl.Utility.Implement
{
    public class FtpHelper: IFtpHelper
    {
        private readonly MySqlContext _dbContext;
        private readonly IHostingEnvironment _hostingEnv;

        public FtpHelper(MySqlContext dbContext, IHostingEnvironment hostingEnv)
        {
            _dbContext = dbContext;
            _hostingEnv = hostingEnv;
        }
        public async Task<FtpResults> RemoteFiles(string fileUrl, string saveAsName)
        {
            var dirUploads = string.Format("{0}/{1}", _hostingEnv.WebRootPath, "Uploads");

            if (!Directory.Exists(dirUploads)) Directory.CreateDirectory(dirUploads);

            var uri = new Uri(fileUrl);
            
            //if (!uri.IsFile) return new FtpResults { IsSuccessful = false };
            
            var filePath = string.Format("{0}/{1}", dirUploads, Path.GetFileName(uri.LocalPath));

            using (var httpClient = new HttpClient())
            {
                using (var response = await httpClient.GetAsync(fileUrl, HttpCompletionOption.ResponseContentRead))
                    using(var streamtoRead = await response.Content.ReadAsStreamAsync())
                {
                    using (var streamtoWrite = File.Open(filePath, FileMode.Create))
                    {
                        await streamtoRead.CopyToAsync(streamtoWrite);
                    }
                }
            }

            var results = UploadFiles(filePath, saveAsName);

            File.Delete(filePath);

            return results;
        }
        public FtpResults UploadFiles(string filePath, string saveAsName)
        {
            var results = new FtpResults { IsSuccessful = false };

            var ftpServer = _dbContext.Servers.FirstOrDefault(x => x.Title == "Image");

            if (ftpServer == null) return results;

            var ftpAccount = JsonConvert.DeserializeObject<FtpAccount>(ftpServer.Data);

            if (ftpAccount == null) return results;

            // create an FTP client
            FtpClient ftpClient = new FtpClient(ftpAccount.host);

            // if you don't specify login credentials, we use the "anonymous" user account
            ftpClient.Credentials = new NetworkCredential(ftpAccount.username, ftpAccount.password);

            // begin connecting to the server
            ftpClient.Connect();

            var fileExtension = new FileInfo(filePath).Extension;
            try
            {
                ftpClient.UploadFile(filePath, "/" + ftpAccount.dir + saveAsName + fileExtension, FtpExists.Overwrite, true);
            }
            catch (Exception ex)
            {
                var er = ex.Message;
            }

            results.IsSuccessful = true;
            results.FileName = saveAsName + fileExtension;
            results.FullPath = string.Format("{0}/{1}{2}{3}", ftpAccount.public_url, ftpAccount.dir, saveAsName, fileExtension);
            results.ServerId = ftpServer.Id;

            ftpClient.Disconnect();

            return results;
        }
    }
}
