﻿using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IBitPornoHelper
    {
        Task<string> RemoteFile(string fileUrl);
        Task<string> RemoteFileStatus(string idRemote);
    }
}
