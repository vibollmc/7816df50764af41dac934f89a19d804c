using JavCrawl.Models;
using JavCrawl.Models.DbEntity;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Dal.Context
{
    public interface IDbRepository
    {
        Task<int> CrawlJavHiHiMovies(JavHiHiMovies javHiHiMovies);
        Task<bool> SaveSchedule(JobListCrawl job);
        IList<JobListCrawl> GetSchedule();

        Task<bool> RunJobCrawl();
    }
}
