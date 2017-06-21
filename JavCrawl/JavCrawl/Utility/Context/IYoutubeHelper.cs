using JavCrawl.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IYoutubeHelper
    {
        Task<bool> Comment(string videoId, string commentText);
        Task<IList<YoutubeVideo>> Search(string keyword, int maxResult);
    }
}
