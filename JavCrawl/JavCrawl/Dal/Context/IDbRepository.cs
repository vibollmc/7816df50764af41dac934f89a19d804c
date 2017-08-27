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
        Task<bool> SaveJavHiHiMovie(JavHiHiMovie movie);

        Task<bool> SaveSchedule(JobListCrawl job);
        IList<JobListCrawl> GetSchedule();

        Task<bool> RunJobCrawl();

        Task<bool> UpdateImage();

        Episodes GetEpisodeToTranferOpenload(HostingLink hosting);

        Task<bool> UpdateEpisodeRemoteId(int id, int remoteid);

        Episodes GetEpisodeToCheckStatusRemote(HostingLink hosting);

        Task<bool> UpdateEpisodeWithNewLink(int id, string link);

        IList<Episodes> GetEpisodesRemoted();
        IList<Episodes> GetEpisodesRemoting();
        IList<Episodes> GetEpisodesRemoteError();

        IList<Films> GetFilmsToGenerateBigSlide();

        Task<bool> NewSlide(IList<int> filmIds);

        IList<YoutubeComment> GetYoutubeComment(IList<string> videoId);
        Task<bool> AddNewYoutubeComment(IList<YoutubeComment> youtube);

        Task<bool> GenerateMemberVideo();

        Task<Stars> GetStar();

        Task<bool> UpdateCrossImage();

        Task<bool> AddGoogleApi(GoogleApi api);

        IList<GoogleApi> GetGoogleApi();
        GoogleApi GetGoogleApi(int id);

        Task<bool> GoogleAuthorized(int apiId);
        Task<bool> UpdateGoogleApiLastUsed(int? apiId);

        GoogleApi GetGoogleApiToUse();
    }
}
