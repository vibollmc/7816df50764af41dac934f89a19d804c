using System;
using System.IO;
using System.Net;
using System.Net.Http;
using System.Threading.Tasks;
using FluentFTP;
using HighLights.Web.Dal.Context;
using HighLights.Web.Utilities.Context;
using HighLights.Web.Utilities.Model;
using Microsoft.AspNetCore.Hosting;

namespace HighLights.Web.Utilities.Implement
{
    public class FtpHelper : IFtpHelper
    {
        private readonly IImageServerRepository _imageServerRepository;
        private readonly IHostingEnvironment _hostingEnvironment;

        public FtpHelper(IImageServerRepository imageServerRepository, IHostingEnvironment hostingEnvironment)
        {
            _imageServerRepository = imageServerRepository;
            _hostingEnvironment = hostingEnvironment;
        }

        public async Task<FtpResult> RemoteFiles(string fileUrl, string saveAsName)
        {
            var dirUploads = string.Format("{0}/{1}", _hostingEnvironment.WebRootPath, "Uploads");

            if (!Directory.Exists(dirUploads)) Directory.CreateDirectory(dirUploads);

            if (fileUrl.StartsWith("//")) fileUrl = "http:" + fileUrl;

            var uri = new Uri(fileUrl);

            //if (!uri.IsFile) return new FtpResults { IsSuccessful = false };

            var filePath = string.Format("{0}/{1}", dirUploads, Path.GetFileName(uri.LocalPath));

            using (var httpClient = new HttpClient())
            {
                using (var response = await httpClient.GetAsync(fileUrl, HttpCompletionOption.ResponseContentRead))
                using (var streamtoRead = await response.Content.ReadAsStreamAsync())
                {
                    using (var streamtoWrite = File.Open(filePath, FileMode.Create))
                    {
                        await streamtoRead.CopyToAsync(streamtoWrite);
                    }
                }
            }

            var results = await UploadFiles(filePath, saveAsName);

            File.Delete(filePath);

            return results;
        }
        public async Task<FtpResult> UploadFiles(string filePath, string saveAsName)
        {
            var results = new FtpResult { IsSuccessful = false };

            var imageServer = await _imageServerRepository.GetActiveImageServer();

            if (imageServer == null) return results;

            // create an FTP client
            var ftpClient = new FtpClient(imageServer.ServerFtp);

            // if you don't specify login credentials, we use the "anonymous" user account
            ftpClient.Credentials = new NetworkCredential(imageServer.UserName, imageServer.Password);

            // begin connecting to the server
            ftpClient.Connect();

            var fileExtension = new FileInfo(filePath).Extension;
            try
            {
                ftpClient.UploadFile(filePath, "/" + imageServer.Patch + saveAsName + fileExtension, FtpExists.Overwrite, true);
            }
            catch (Exception ex)
            {
                var er = ex.Message;
            }

            results.IsSuccessful = true;
            results.FileName = saveAsName + fileExtension;
            results.FullPath = $"{imageServer.ServerUrl}/{imageServer.Patch}{saveAsName}{fileExtension}";
            if (imageServer.Id != null) results.ServerId = imageServer.Id.Value;

            ftpClient.Disconnect();

            return results;
        }
    }
}
