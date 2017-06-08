using JavCrawl.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Dal.Context
{
    public interface IDbRepository
    {
        Task<int> CrawlJavHiHiMovies(JavHiHiMovies javHiHiMovies);
    }
}
