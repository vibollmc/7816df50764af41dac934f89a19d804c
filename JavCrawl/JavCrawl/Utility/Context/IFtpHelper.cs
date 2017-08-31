using JavCrawl.Models;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IFtpHelper
    {
        Task<FtpResults> RemoteFiles(string fileUrl, string saveAsName);
        FtpResults UploadFiles(string filePath, string saveAsName);
    }
}
