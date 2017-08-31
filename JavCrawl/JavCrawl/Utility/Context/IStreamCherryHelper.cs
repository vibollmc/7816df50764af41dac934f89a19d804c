using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IStreamCherryHelper
    {
        Task<string> RemoteFile(string fileUrl);
        Task<string> RemoteFileStatus(string idRemote, int filmId);
    }
}
