using System.Threading.Tasks;
using Football.Show.Utilities.Model;

namespace Football.Show.Utilities.Context
{
    public interface IFtpHelper
    {
        Task<FtpResult> RemoteFiles(string fileUrl, string saveAsName);
        Task<FtpResult> UploadFiles(string filePath, string saveAsName);
    }
}
