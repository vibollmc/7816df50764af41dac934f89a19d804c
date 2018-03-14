using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Utilities.Model;

namespace HighLights.Web.Utilities.Context
{
    public interface IFtpHelper
    {
        Task<FtpResult> RemoteFiles(string fileUrl, string saveAsName);
        Task<FtpResult> UploadFiles(string filePath, string saveAsName);
    }
}
