using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IOpenloadHelper
    {
        Task<string> RemoteFile(string fileUrl);
        Task<string> RemoteFileStatus(string idRemote, int filmId);
    }
}
