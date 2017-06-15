using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IOpenloadHelper
    {
        Task<int> RemoteFile(string fileUrl);
        Task<string> RemoteFileStatus(int idRemote);
        Task<bool> RenameFile(string fileId, string newName);
    }
}
