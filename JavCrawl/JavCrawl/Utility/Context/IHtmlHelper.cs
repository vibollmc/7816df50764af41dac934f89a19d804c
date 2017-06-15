using JavCrawl.Models;
using JavCrawl.Models.DbEntity;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Context
{
    public interface IHtmlHelper
    {
        Task<JavHiHiMovies> GetJavHiHiMovies(string url);
        Task<Stars> GetJavHiHiStar(string name, string fromSite);
        Task<JavHiHiMovies> GetJavMovies(string url);
    }
}
