﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using JavCrawl.Models;
using HtmlAgilityPack;
using System.Net.Http;
using Newtonsoft.Json;
using JavCrawl.Utility.Context;
using JavCrawl.Models.DbEntity;
using JavCrawl.Dal;

namespace JavCrawl.Utility.Implement
{
    public class HtmlHelper : IHtmlHelper
    {
        private class LinkEpsAndDescription
        {
            public LinkEpsAndDescription()
            {
                LinkEps = new List<string>();
            }
            public string Description { get; set; }
            public IList<string> LinkEps { get; set; }
        }

        private readonly MySqlContext _dbContext;

        public HtmlHelper(MySqlContext dbContext)
        {
            _dbContext = dbContext;
        }

        public async Task<IList<VideoApi>> GetRedirectLinkVideo(string url)
        {
            var from789 = url.Contains("jav789.com");

            var htmlWeb = new HtmlWeb();

            var htmlDoc = await htmlWeb.LoadFromWebAsync(url);

            var sourceNodes = htmlDoc.DocumentNode.Descendants("source");

            if (sourceNodes == null || sourceNodes.Count() == 0)
            {
                var aNodes = htmlDoc.DocumentNode.Descendants("a").Where(x => x.Attributes.Contains("href") && x.Attributes["href"].Value.Contains(".googlevideo.com"));

                if (aNodes == null) return null;
                var resulta = new List<VideoApi>();
                foreach (var a in aNodes)
                {
                    var label = a.InnerText.Trim();

                    var file = a.Attributes["href"].Value;

                    file = file.Substring(file.IndexOf(".googlevideo.com"));

                    file = file.Substring(0, file.IndexOf("&title="));

                    file = "https://redirector" + file;

                    resulta.Add(new VideoApi
                    {
                        Default = label.Contains("720").ToString(),
                        File = file,
                        Src = file,
                        Label = label,
                        Type = "video/mp4"
                    });
                }
                return resulta;
            }

            var sourceLinks = sourceNodes.Where(
                x => 
                    x.Attributes.Contains("type") && x.Attributes["type"].Value == "video/mp4" &&
                    x.Attributes.Contains("src") && x.Attributes["src"].Value != string.Empty);

            var result = new List<VideoApi>();

            foreach(var nodeLink in sourceLinks)
            {
                var label = nodeLink.Attributes.Contains("data-res") ? nodeLink.Attributes["data-res"].Value : "HD";

                var file = nodeLink.Attributes["src"].Value;

                file = file.Substring(file.IndexOf(".googlevideo.com"));

                file = "https://redirector" + file;

                result.Add(new VideoApi
                {
                    Default = label.Contains("720").ToString(),
                    File = file,
                    Src = file,
                    Label = label,
                    Type = nodeLink.Attributes["type"].Value
                });
            }

            return result;
        }

        public async Task<JavHiHiMovies> GetJavMovies(string url)
        {
            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var from789 = url.Contains("jav789.com");

            var results = JsonConvert.DeserializeObject<JavHiHiMovies>(json);

            foreach (var item in results.movies)
            {
                item.fromsite = "hihi";
                if (from789)
                {
                    item.fromsite = "789";
                }

                item.url = item.url.Substring(item.url.IndexOf('/') + 1, item.url.IndexOf('.') - item.url.IndexOf('/') - 1);
            }
            results.movies = results.movies.Where(x => x.url != string.Empty).ToList();
            return results;
        }


        public async Task<Stars> GetJavHiHiStar(string name, string fromSite)
        {
            Stars results = null;
            var url = string.Format("http://javhihi.com/japanese-av/{0}.html", name.Trim().Replace(" ", "-").ToLower());
            if (fromSite == "789")
            {
                url = string.Format("http://jav789.com/pornstar/{0}.html", name.Trim().Replace(" ", "-").ToLower());
            }
            var htmlWeb = new HtmlWeb();

            var htmlDoc = await htmlWeb.LoadFromWebAsync(url);

            var divNodes = htmlDoc.DocumentNode.Descendants("div");

            if (divNodes == null || divNodes.Count() == 0) return results;

            var divDetails = divNodes.FirstOrDefault(x => x.Attributes.Contains("class")
                    && x.Attributes["class"].Value == "pornstar-detail-info");
            if (divDetails != null) {
                results = new Stars();

                results.CreatedAt = DateTime.Now;

                results.Title = name.Trim();
                results.TitleAscii = name.Trim().ToLower();
                results.Slug = name.Trim().Replace(" ", "-").ToLower();

                results.Seo = "{\"title\":\"" + name.Trim() + "\",\"keyword\":\"" + name.Trim() + ", " + name.Trim() + " jav hd, videos "+ name.Trim() + ", " + name.Trim() + " sexy\",\"description\":\"" + name.Trim() + ", " + name.Trim() + " jav hd, videos " + name.Trim() + ", " + name.Trim() + " sexy\"}";
                divDetails.ChildNodes["center"].Remove();

                results.Story = divDetails.InnerHtml;

                foreach (var node in divDetails.ChildNodes)
                {
                    if (node.InnerText.Contains("Height"))
                    {
                        results.Height = node.NextSibling.NextSibling.InnerText.Trim();
                    }
                    else if (node.InnerText.Contains("Weight"))
                    {
                        var weight = node.NextSibling.NextSibling.InnerText.Trim();
                    }
                    else if (node.InnerText.Contains("Measurements"))
                    {
                        var measurements = node.NextSibling.NextSibling.InnerText.Trim();
                    }
                    else if (node.InnerText.Contains("Birthday"))
                    {
                        results.Birth = node.NextSibling.NextSibling.InnerText.Trim();
                    }
                    else if (node.InnerText.Contains("Country"))
                    {
                        results.HomeTown = node.NextSibling.NextSibling.InnerText.Trim();
                    }
                }
                
            }
            var imgNodes = htmlDoc.DocumentNode.Descendants("img");

            if (imgNodes == null || imgNodes.Count() == 0) return results;

            var img = imgNodes.FirstOrDefault(x => x.Attributes.Contains("class")
                    && x.Attributes["class"].Value == "img-responsive");

            if (img != null) results.ThumbName = img.Attributes["src"].Value;

            return results;
        }

        public async Task<JavHiHiMovies> GetJavHiHiMovies(string url)
        {
            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;
            
            var from789 = url.Contains("jav789.com");

            var results = JsonConvert.DeserializeObject<JavHiHiMovies>(json);

            foreach (var item in results.movies)
            {
                var urlPage = string.Format("http://javhihi.com/{0}", item.url);
                item.fromsite = "hihi";
                if (from789)
                {
                    urlPage = string.Format("http://jav789.com/{0}", item.url);
                    item.fromsite = "789";
                }

                item.url = item.url.Substring(item.url.IndexOf('/') + 1, item.url.IndexOf('.') - item.url.IndexOf('/') - 1);

                if (_dbContext.Films.Any(x => x.Slug == item.url))
                {
                    item.url = string.Empty; //Marked Remove;
                }
                else
                {
                    try
                    {
                        var linkEpsAndDecs = await GetJavHiHiMoviesLinkEpisode(urlPage);

                        if (from789)
                        {
                            item.descriptions = item.name;
                        }
                        else
                        {
                            item.descriptions = string.IsNullOrWhiteSpace(linkEpsAndDecs.Description) ? item.name : linkEpsAndDecs.Description;
                        }

                        item.linkepisode = linkEpsAndDecs.LinkEps;
                    }
                    catch
                    {
                        item.url = string.Empty;
                    }
                    
                }
            }
            results.movies = results.movies.Where(x => x.url != string.Empty).ToList();
            return results;
        }

        private async Task<string> GetLinkHiddenEps(string url)
        {
            string results = null;
            var htmlWeb = new HtmlWeb();
            var htmlDoc = await htmlWeb.LoadFromWebAsync(url);

            var divNodes = htmlDoc.DocumentNode.Descendants("div");

            if (divNodes == null || divNodes.Count() == 0) return results;

            var playerSize = divNodes.FirstOrDefault(
                x => x.Attributes.Contains("class") 
                    && x.Attributes["class"].Value == "player-size");

            if (playerSize != null && playerSize.ChildNodes != null && playerSize.ChildNodes.Count > 0
                && playerSize.ChildNodes["iframe"] != null)
                results = playerSize.ChildNodes["iframe"].Attributes["src"].Value;

            return results;
        }

        private async Task<LinkEpsAndDescription> GetJavHiHiMoviesLinkEpisode(string url)
        {
            var results = new LinkEpsAndDescription();
            var htmlWeb = new HtmlWeb();
            var htmlDoc = await htmlWeb.LoadFromWebAsync(url);

            var divNodes = htmlDoc.DocumentNode.Descendants("div");

            if (divNodes == null || divNodes.Count() == 0) return results;
            

            if (divNodes.Any(
                    x => x.Attributes.Contains("class") 
                        && x.Attributes["class"].Value.Contains("video-comming-soon")))
                throw new Exception("Video comming soon");

            var serverNodes = divNodes.Where(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "server");

            if (serverNodes == null || serverNodes.Count() == 0)
            {
                results.LinkEps.Add(url);
            }
            else
            {
                foreach (var serverNode in serverNodes)
                {
                    if (serverNode.InnerText.Contains("Server YT") && serverNode.ChildNodes != null && serverNode.ChildNodes.Count > 1)
                    {
                        results.LinkEps.Add(serverNode.ChildNodes["a"].Attributes["href"].Value);
                    }
                    else
                    {
                        if (serverNode.ChildNodes["a"].Attributes["href"].Value == url)
                        {
                            var playerSize = divNodes.FirstOrDefault(
                                x => x.Attributes.Contains("class")
                                    && x.Attributes["class"].Value == "player-size");

                            if (playerSize != null && playerSize.ChildNodes != null && playerSize.ChildNodes.Count > 0
                                && playerSize.ChildNodes["iframe"] != null)
                                results.LinkEps.Add(playerSize.ChildNodes["iframe"].Attributes["src"].Value);
                        }
                        else if (serverNode.ChildNodes != null && serverNode.ChildNodes.Count > 1)
                        {
                            var urlEps = serverNode.ChildNodes["a"].Attributes["href"].Value;
                            var linkEps = await GetLinkHiddenEps(urlEps);

                            if (linkEps != null)
                            {
                                results.LinkEps.Add(linkEps);
                            }
                        }
                    }
                }
            }

            var pNodes = htmlDoc.DocumentNode.Descendants("p");

            if (pNodes == null || pNodes.Count() == 0) return results;

            var longtextNode = pNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "long-text");

            if (longtextNode != null)
            {
                results.Description = longtextNode.InnerText.Trim();
            }

            return results;
        }
    }
}
