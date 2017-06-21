using JavCrawl.Utility.Context;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using JavCrawl.Models;
using Google.Apis.YouTube.v3;
using Google.Apis.Services;
using Google.Apis.Auth.OAuth2;
using System.Threading;
using System.IO;
using Google.Apis.Util.Store;

namespace JavCrawl.Utility.Implement
{
    public class YoutubeHelper : IYoutubeHelper
    {
        public async Task<bool> Comment(string videoId, string channelId, string commentText)
        {
            var credential = await GoogleWebAuthorizationBroker.AuthorizeAsync(
                    new ClientSecrets {
                        ClientId = "723450826839-pri20hqbueba3f7je99b1mbd73pgj2ul.apps.googleusercontent.com",
                        ClientSecret = "WzsiiOdI52r4RsAHd7q8a1wO"
                    },
                    // This OAuth 2.0 access scope allows for full read/write access to the
                    // authenticated user's account.
                    new[] { YouTubeService.Scope.Youtube, YouTubeService.Scope.YoutubeForceSsl },
                    "user",
                    CancellationToken.None
                );
            

            var youtubeService = new YouTubeService(new BaseClientService.Initializer()
            {
                //ApiKey = "AIzaSyBLMWRrG0IrQefrbZ6-usjawv0GU4Xkg4s",
                HttpClientInitializer = credential,
                ApplicationName = "YTCommenter"
            });

            var commentRequest = youtubeService.CommentThreads.Insert(new Google.Apis.YouTube.v3.Data.CommentThread()
            {
                Snippet = new Google.Apis.YouTube.v3.Data.CommentThreadSnippet
                {
                    ChannelId = channelId,
                    VideoId = videoId,
                    TopLevelComment = new Google.Apis.YouTube.v3.Data.Comment()
                    {
                        Snippet = new Google.Apis.YouTube.v3.Data.CommentSnippet()
                        {
                            TextOriginal = commentText
                        }
                    }
                }
            }, "snippet");

            var commentResponse = await commentRequest.ExecuteAsync();

            return true;
        }

        public async Task<IList<YoutubeVideo>> Search(string keyword, int maxResult)
        {
            var youtubeService = new YouTubeService(new BaseClientService.Initializer()
            {
                ApiKey = "AIzaSyBLMWRrG0IrQefrbZ6-usjawv0GU4Xkg4s",
                ApplicationName = "YTCommenter"
            });

            var searchListRequest = youtubeService.Search.List("snippet");
            searchListRequest.Q = keyword;
            searchListRequest.Type = "video";
            searchListRequest.MaxResults = maxResult;

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
                            ChannelId = searchResult.Snippet.ChannelId
                        };

                        results.Add(video);
                        break;
                }
            }

            return results;
        }
    }
}
