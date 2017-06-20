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

        Task<bool> UpdateImage();

        Episodes GetEpisodeToTranferOpenload();

        Task<bool> UpdateEpisodeRemoteId(int id, int remoteid);

        Episodes GetEpisodeToCheckStatusRemote();

        Task<bool> UpdateEpisodeWithNewLink(int id, string link);

        IList<Episodes> GetEpisodesRemoted();
        IList<Episodes> GetEpisodesRemoting();
        IList<Episodes> GetEpisodesRemoteError();

        IList<Films> GetFilmsToGenerateBigSlide();

        Task<bool> NewSlide(IList<int> filmIds);
    }
}
