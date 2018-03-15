using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using HighLights.Web.Dal.Context;
using HighLights.Web.Entities;
using HighLights.Web.Entities.Enum;
using HighLights.Web.Utilities.Context;
using HighLights.Web.Utilities.Model;
using HtmlAgilityPack;

namespace HighLights.Web.Utilities.Implement
{
    public class Crawler : ICrawler
    {
        private readonly ICrawlLinkRepository _crawlLinkRepository;
        private readonly IMatchRepository _matchRepository;
        private readonly IFtpHelper _ftpHelper;

        public Crawler(ICrawlLinkRepository crawlLinkRepository, IMatchRepository matchRepository, IFtpHelper ftpHelper)
        {
            _crawlLinkRepository = crawlLinkRepository;
            _matchRepository = matchRepository;
            _ftpHelper = ftpHelper;
        }

        private async Task CrawlData()
        {
            try
            {
                var crawlLink = await _crawlLinkRepository.GetActive();

                if (crawlLink == null) return;

                var url = crawlLink.BaseLink;

                if (crawlLink.FromPage.HasValue && crawlLink.ToPage.HasValue)
                {

                    if (crawlLink.Finished == null)
                        crawlLink.Finished = crawlLink.FromPage > crawlLink.ToPage
                            ? crawlLink.FromPage
                            : crawlLink.ToPage;
                    else crawlLink.Finished--;

                    url = string.Format(url, crawlLink.Finished);
                }


                var matchLinks = await GetMatchLinks(url);

                if (matchLinks == null) return;

                foreach (var matchLink in matchLinks)
                {
                    var exsits = await _matchRepository.CheckExsits(matchLink.Slug);

                    if (!exsits) await SaveMatch(matchLink);
                }

                //await _crawlLinkRepository.UpdateFinished(crawlLink.Id);
            }
            catch (Exception ex)
            {
                
            }

            return;
        }

        private async Task<bool> SaveMatch(MatchLink matchLink)
        {
            var htmlWeb = new HtmlWeb();
            var htmlDoc = await htmlWeb.LoadFromWebAsync(matchLink.Link);

            var articleNode = htmlDoc.DocumentNode.Descendants("article").FirstOrDefault();

            var divNodes = articleNode?.Descendants("div");

            var divVcRow = divNodes?.FirstOrDefault(x =>
                x.Attributes.Contains("class") && x.Attributes["class"].Value == "vc_row wpb_row td-pb-row");

            var divWpbWrapper =
                divVcRow?.Descendants("div")?.LastOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "wpb_wrapper");

            if (divWpbWrapper == null) return false;

            var match = new Match();
            match.Slug = matchLink.Slug;
            match.Title = matchLink.Name;

            foreach (var childNode in divWpbWrapper.ChildNodes)
            {
                if (childNode.PreviousSibling == null) continue;

                if (childNode.PreviousSibling.InnerText.Contains("Competition"))
                    match.Competition = childNode.InnerText;

                if (childNode.PreviousSibling.InnerText.Contains("Date"))
                    match.MatchDate = childNode.InnerText.ToDate();

                if (childNode.PreviousSibling.InnerText.Contains("Stadium"))
                    match.Stadium = childNode.InnerText;

                if (childNode.PreviousSibling.InnerText.Contains("Referee"))
                    match.Referee = childNode.InnerText;
            }

            var divHeaderTeam1 = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "headerteam1");
            if (divHeaderTeam1 != null)
            {
                match.Home = divHeaderTeam1.ChildNodes[0].InnerText;
            }

            var divHeaderTeam2 = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "headerteam2");
            if (divHeaderTeam2 != null)
            {
                match.Home = divHeaderTeam2.ChildNodes[0].InnerText;
            }

            var divScore = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "score");
            if (divScore != null)
            {
                match.Score = $"{divScore.ChildNodes[0].InnerText}\" : \"{divScore.ChildNodes[2].InnerText}";
            }

            var divTeam1roster = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "team1roster");
            var formations = new List<Formation>();
            if (divTeam1roster != null)
            {
                match.HomeManager = divTeam1roster.ChildNodes[1].InnerText;
                foreach (var liNode in divTeam1roster.Descendants("li"))
                {
                    formations.Add(new Formation
                    {
                        Type = FormationType.Home,
                        Number = liNode.ChildNodes[0].InnerText.ToInt(),
                        Name = liNode.ChildNodes[1].InnerText,
                        IsSubstitution = liNode.Attributes.Contains("class") && liNode.Attributes["class"].Value == "issub"
                    });
                }
            }
            var divTeam2roster = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "team2roster");
            if (divTeam2roster != null)
            {
                match.HomeManager = divTeam2roster.ChildNodes[1].InnerText;

                foreach (var liNode in divTeam2roster.Descendants("li"))
                {
                    formations.Add(new Formation
                    {
                        Type = FormationType.Home,
                        Number = liNode.ChildNodes[0].InnerText.ToInt(),
                        Name = liNode.ChildNodes[1].InnerText,
                        IsSubstitution = liNode.Attributes.Contains("class") && liNode.Attributes["class"].Value == "issub"
                    });
                }
            }

            var substitutions = new List<Substitution>();
            var divTeam1subs = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "team1subs");
            if (divTeam1subs != null)
            {
                foreach (var liNode in divTeam1subs.Descendants("li"))
                {
                    substitutions.Add(new Substitution
                    {
                        Type = FormationType.Home,
                        Number = liNode.ChildNodes[0].InnerText.ToInt(),
                        Name = liNode.ChildNodes[1].InnerText
                    });
                }
            }
            var divTeam2subs = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "team2subs");
            if (divTeam2subs != null)
            {
                foreach (var liNode in divTeam1subs.Descendants("li"))
                {
                    substitutions.Add(new Substitution
                    {
                        Type = FormationType.Home,
                        Number = liNode.ChildNodes[0].InnerText.ToInt(),
                        Name = liNode.ChildNodes[1].InnerText
                    });
                }
            }

            return true;
        }

        private async Task<IEnumerable<MatchLink>> GetMatchLinks(string url)
        {
            var htmlWeb = new HtmlWeb();
            var htmlDoc = await htmlWeb.LoadFromWebAsync(url);

            var divNodes = htmlDoc.DocumentNode.Descendants("div");

            if (divNodes == null || !divNodes.Any()) return null;

            var divMainContent = divNodes.FirstOrDefault(x =>
                x.Attributes.Contains("class") && x.Attributes["class"].Value == "td-ss-main-content");

            if (divMainContent == null) return null;

            var results = new List<MatchLink>();

            foreach (var nodeBlock in divMainContent.ChildNodes)
            {
                if (!(nodeBlock.Attributes.Contains("class") && nodeBlock.Attributes["class"].Value == "td-block-row")) continue;

                foreach (var node in nodeBlock.ChildNodes)
                {
                    var timeNode = node.Descendants("time");

                    var matchLink = new MatchLink();

                    if (timeNode.Any()) matchLink.Date = timeNode.First().InnerHtml;

                    var aNodes = node.Descendants("a");

                    foreach (var aNode in aNodes)
                    {
                        if (aNode.Descendants("img").Any())
                        {
                            matchLink.ImageLink = aNode.ChildNodes[0].Attributes["src"].Value;
                        }
                        else
                        {
                            matchLink.Name = aNode.InnerHtml.Replace("&#8211;", "-");
                            matchLink.Link = aNode.Attributes["href"].Value;
                        }
                    }

                    results.Add(matchLink);
                }
            }

            return results;
        }

        public async Task Run()
        {
            await CrawlData();
        }
    }
}
