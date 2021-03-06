﻿using JavCrawl.Models;
using System;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IYoutubeHelper
    {
        Task<string> Authorization(int apiId);
        Task<bool> Comment(IList<string> videoId, string commentText);
        Task<IList<YoutubeVideo>> Search(string keyword, int maxResult, decimal? lat, decimal? lon, string radius, DateTime? publishedAfter, string pageToken);
    }
}
