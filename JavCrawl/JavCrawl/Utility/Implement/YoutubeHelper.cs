using JavCrawl.Utility.Context;
using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using JavCrawl.Models;
using Google.Apis.YouTube.v3;
using Google.Apis.Services;
using System.Threading;
using System.IO;
using Google.Apis.Util.Store;
using Microsoft.AspNetCore.Hosting;
using Google.Apis.YouTube.v3.Data;
using Microsoft.Extensions.Options;
using JavCrawl.Dal.Context;
using Google.Apis.Auth.OAuth2;
using JavCrawl.Models.DbEntity;

namespace JavCrawl.Utility.Implement
{
    public class YoutubeHelper : IYoutubeHelper
    {
        private readonly IHostingEnvironment _hostingEnv;
        private readonly YoutubeSettings _youtubeSettings;
        private readonly IDbRepository _dbRepository;
        public YoutubeHelper(IHostingEnvironment hostingEnv, IOptions<YoutubeSettings> youtubeSettings, IDbRepository dbRepository)
        {
            _hostingEnv = hostingEnv;
            _youtubeSettings = youtubeSettings.Value;
            _dbRepository = dbRepository;
        }

        public async Task<string> Authorization(int apiId)
        {
            try
            {
                var api = _dbRepository.GetGoogleApi(apiId);

                var dirUploads = string.Format("{0}/{1}/{2}", _hostingEnv.WebRootPath, "Uploads", "GoogleApi");

                using (var stream = new FileStream(dirUploads + "/" + api.FileName, FileMode.Open, FileAccess.Read))
                {
                    var dirStore = dirUploads + "/Api" + api.Id;

                    if (!Directory.Exists(dirStore)) Directory.CreateDirectory(dirStore);

                    var credential = await GoogleWebAuthorizationBroker.AuthorizeAsync(
                        stream,
                        new[] { YouTubeService.Scope.YoutubeForceSsl
                        },
                        "user",
                        CancellationToken.None,
                        new FileDataStore(dirStore)
                    );

                    var youtubeService = new YouTubeService(new BaseClientService.Initializer()
                    {
                        ApiKey = _youtubeSettings.ApiKey,
                        HttpClientInitializer = credential,
                        ApplicationName = _youtubeSettings.ApplicationName
                    });

                    if (credential != null)
                    {
                        await _dbRepository.GoogleAuthorized(apiId);
                    }
                }

                return "Authorized";
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.Message);
                return ex.Message;
            }
        }

        public async Task<bool> Comment(IList<string> videoId, string commentText)
        {
            try
            {
                var api = _dbRepository.GetGoogleApiToUse();

                var dirUploads = string.Format("{0}/{1}/{2}", _hostingEnv.WebRootPath, "Uploads", "GoogleApi");

                using (var stream = new FileStream(dirUploads + "/" + api.FileName, FileMode.Open, FileAccess.Read))
                {
                    var dirStore = dirUploads + "/Api" + api.Id;

                    if (!Directory.Exists(dirStore)) Directory.CreateDirectory(dirStore);

                    var credential = await GoogleWebAuthorizationBroker.AuthorizeAsync(
                        stream,
                        new[] { YouTubeService.Scope.YoutubeForceSsl
                        },
                        "user",
                        CancellationToken.None,
                        new FileDataStore(dirStore)
                    );

                    var youtubeService = new YouTubeService(new BaseClientService.Initializer()
                    {
                        ApiKey = api.ApiKey,
                        HttpClientInitializer = credential,
                        ApplicationName = api.Name
                    });

                    var commented = new List<YoutubeComment>();


                    foreach (var vi in videoId)
                    {
                        try
                        {
                            var arr = vi.Split('|');

                            var commentThread = new CommentThread();
                            commentThread.Snippet = new CommentThreadSnippet();
                            commentThread.Snippet.VideoId = arr[0];
                            commentThread.Snippet.ChannelId = arr[1];
                            commentThread.Snippet.TopLevelComment = new Comment();
                            commentThread.Snippet.TopLevelComment.Snippet = new CommentSnippet();
                            commentThread.Snippet.TopLevelComment.Snippet.TextOriginal = commentText;

                            var request = youtubeService.CommentThreads.Insert(commentThread, "snippet");

                            var response = await request.ExecuteAsync();

                            commented.Add(new YoutubeComment
                            {
                                CreatedAt = DateTime.Now,
                                ChannelId = arr[1],
                                VideoId = arr[0]
                            });
                        }
                        catch(Exception ex)
                        {
                            Console.WriteLine(ex.Message);
                            //Do Nothing to skip video do not allow comment
                        }
                    }

                    await _dbRepository.AddNewYoutubeComment(commented);

                    await _dbRepository.UpdateGoogleApiLastUsed(api.Id);
                }

                return true;
            }
            catch
            {
                return false;
            }
        }

        public async Task<IList<YoutubeVideo>> Search(string keyword, int maxResult, decimal? lat, decimal? lon, string radius, DateTime? publishedAfter, string pageToken)
        {
            var api = _dbRepository.GetGoogleApiToUse();

            var youtubeService = new YouTubeService(new BaseClientService.Initializer()
            {
                ApiKey = api.ApiKey,
                ApplicationName = api.Name
            });

            var searchListRequest = youtubeService.Search.List("snippet");
            
            searchListRequest.Q = keyword;
            searchListRequest.Type = "video";
            searchListRequest.MaxResults = maxResult;

            if (lat != null && lon != null)
            {
                searchListRequest.Location = string.Format("{0:0.00},{1:0.00}", lat, lon);

                if (!string.IsNullOrWhiteSpace(radius)) searchListRequest.LocationRadius = radius;
            }

            if (publishedAfter != null) searchListRequest.PublishedAfter = publishedAfter;

            if (!string.IsNullOrWhiteSpace(pageToken)) searchListRequest.PageToken = pageToken;

            var searchListResponse = await searchListRequest.ExecuteAsync();

            var results = new List<YoutubeVideo>();

            foreach (var searchResult in searchListResponse.Items)
            {
                switch (searchResult.Id.Kind)
                {
                    case "youtube#video":
                        var video = new YoutubeVideo
                        {
                            Title = searchResult.Snippet.Title,
                            VideoId = searchResult.Id.VideoId,
                            PublishedAt = searchResult.Snippet.PublishedAt,
                            ChannelId = searchResult.Snippet.ChannelId,
                            TokenNextPage = searchListResponse.NextPageToken,
                            TokenPrevPage = searchListResponse.PrevPageToken
                        };

                        results.Add(video);
                        break;
                }
            }

            return results;
        }
    }
}
