using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IBitPornoHelper
    {
        Task<int> RemoteFile(string fileUrl);
        Task<string> RemoteFileStatus(int idRemote);
        Task<bool> JobRemoteFile();
    }
}
