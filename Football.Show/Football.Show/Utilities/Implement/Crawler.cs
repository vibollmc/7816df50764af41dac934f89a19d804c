using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Football.Show.Entities;
using Football.Show.Entities.Enum;
using Football.Show.Utilities.Context;
using Football.Show.Utilities.Model;
using HtmlAgilityPack;

namespace Football.Show.Utilities.Implement
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
                    var page = crawlLink.FromPage > crawlLink.ToPage
                            ? crawlLink.FromPage
                            : crawlLink.ToPage;
                    if (crawlLink.Finished.HasValue) page = page - crawlLink.Finished;
                    url = string.Format(url, page);
                }


                var matchLinks = await GetMatchLinks(url);

                if (matchLinks == null) return;

                foreach (var matchLink in matchLinks)
                {
                    var exsits = await _matchRepository.CheckExsits(matchLink.Slug);

                    if (!exsits) await SaveMatch(matchLink);
                }

                //for test
                //await SaveMatch(new MatchLink
                //{
                //    Link = "http://www.fullmatchesandshows.com/2018/03/14/paris-saint-germain-vs-angers-highlights-full-match-video/",
                //    Name = "Paris Saint Germain vs Angers – Highlights & Full Match",
                //    ImageLink = "https://i1.wp.com/www.fullmatchesandshows.com/wp-content/uploads/2018/03/Paris-Saint-Germain-vs-Angers.jpg?w=600",
                //    Date = "Mar 14, 2018"
                //});


                await _crawlLinkRepository.UpdateFinished(crawlLink.Id);
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

            var iframeNode = articleNode?.Descendants("iframe");

            var ulNodes = articleNode?.Descendants("ul");

            var divNodes = articleNode?.Descendants("div");

            var divVcRow = divNodes?.FirstOrDefault(x =>
                x.Attributes.Contains("class") && 
                x.Attributes["class"].Value.Contains("vc_row") && 
                x.Attributes["class"].Value.Contains("wpb_row") &&
                x.Attributes["class"].Value.Contains("td-pb-row"));

            var divWpbWrapper =
                divVcRow?.Descendants("div")?.LastOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "wpb_wrapper");

            if (divWpbWrapper == null || !iframeNode.Any()) return false;

            var clips = new List<Clip>();
            clips.AddRange(iframeNode.Where(x => 
                x.Attributes.Contains("data-lazy-src") && 
                !x.Attributes["data-lazy-src"].Value.Contains("facebook.com"))
            .Select((x, i) => new Clip
            {
                Url = x.Attributes["data-lazy-src"].Value,
                ClipType = ClipType.HighLight,
                LinkType = LinkType.Embed,
                Name = "Server " + (i + 1)
            }));

            if (!clips.Any()) return false;

            var match = new Match();
            match.Slug = matchLink.Slug;
            match.Title = matchLink.Name;

            var ftpResult = await _ftpHelper.RemoteFiles(matchLink.ImageLink, match.Slug);

            if (ftpResult != null)
            {
                match.ImageName = ftpResult.FileName;
                match.ImageServerId = ftpResult.ServerId;
            }

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

            if (match.MatchDate == null) match.MatchDate = matchLink.RDateTime;

            var divHeaderTeam1 = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "headerteam1");
            if (divHeaderTeam1 != null)
            {
                match.Home = string.Empty;
                foreach (var h2 in divHeaderTeam1.Descendants("h2"))
                {
                    match.Home += " " + h2.InnerText;
                }
                match.Home = match.Home.Trim();

                foreach (var node in divHeaderTeam1.Descendants("div"))
                {
                    match.HomePersonScored = string.IsNullOrWhiteSpace(match.HomePersonScored)
                        ? node.InnerText
                        : ("|" + node.InnerText);
                }
            }
            else
            {
                //td-tags td-post-small-box clearfix
                var tagNode = ulNodes?.FirstOrDefault(x =>
                    x.Attributes.Contains("class") && 
                    x.Attributes["class"].Value.Contains("td-tags") &&
                    x.Attributes["class"].Value.Contains("td-post-small-box"));

                if (tagNode != null)
                {
                    match.Home = tagNode.ChildNodes[1].ChildNodes[0].InnerText;
                }
            }

            var divHeaderTeam2 = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "headerteam2");
            if (divHeaderTeam2 != null)
            {
                match.Away = string.Empty;
                foreach (var h2 in divHeaderTeam2.Descendants("h2"))
                {
                    match.Away += " " + h2.InnerText;
                }
                match.Away = match.Away.Trim();

                if (divHeaderTeam2.ChildNodes.Count > 1)
                {
                    foreach (var node in divHeaderTeam2.Descendants("div"))
                    {
                        match.AwayPersonScored = string.IsNullOrWhiteSpace(match.AwayPersonScored) ? node.InnerText : ("|" + node.InnerText);
                    }
                }
            }
            else
            {
                //td-tags td-post-small-box clearfix
                var tagNode = ulNodes?.FirstOrDefault(x =>
                    x.Attributes.Contains("class") &&
                    x.Attributes["class"].Value.Contains("td-tags") &&
                    x.Attributes["class"].Value.Contains("td-post-small-box"));

                if (tagNode != null)
                {
                    match.Away = tagNode.ChildNodes[2].ChildNodes[0].InnerText;
                }
            }

            var divScore = divNodes.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "score");
            if (divScore != null)
            {
                match.Score = $"{divScore.ChildNodes[0].InnerText} : {divScore.ChildNodes[2].InnerText}";
            }

            var divTeam1Roster = ulNodes?.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "team1roster");
            var formations = new List<Formation>();
            if (divTeam1Roster != null)
            {
                match.HomeManager = divTeam1Roster.ChildNodes[1].InnerText;
                formations.AddRange(divTeam1Roster.Descendants("li")
                    .Select(liNode => new Formation
                    {
                        Type = FormationType.Home,
                        Number = liNode.ChildNodes[0].InnerText.ToInt(),
                        Name = liNode.ChildNodes[1].InnerText,
                        IsSubstitution = liNode.Attributes.Contains("class") && liNode.Attributes["class"].Value == "issub"
                    }));
            }
            var divTeam2Roster = ulNodes?.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "team2roster");
            if (divTeam2Roster != null)
            {
                match.HomeManager = divTeam2Roster.ChildNodes[1].InnerText;
                formations.AddRange(divTeam2Roster.Descendants("li")
                    .Select(liNode => new Formation
                    {
                        Type = FormationType.Home,
                        Number = liNode.ChildNodes[0].InnerText.ToInt(),
                        Name = liNode.ChildNodes[1].InnerText,
                        IsSubstitution = liNode.Attributes.Contains("class") && liNode.Attributes["class"].Value == "issub"
                    }));
            }

            var substitutions = new List<Substitution>();
            var divTeam1Subs = ulNodes?.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "team1subs");
            if (divTeam1Subs != null)
            {
                substitutions.AddRange(divTeam1Subs.Descendants("li")
                    .Select(liNode => new Substitution
                    {
                        Type = FormationType.Home,
                        Number = liNode.ChildNodes[0].InnerText.ToInt(),
                        Name = liNode.ChildNodes[1].InnerText
                    }));
            }
            var divTeam2Subs = ulNodes?.FirstOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "team2subs");
            if (divTeam2Subs != null)
            {
                substitutions.AddRange(divTeam2Subs.Descendants("li")
                    .Select(liNode => new Substitution
                    {
                        Type = FormationType.Home,
                        Number = liNode.ChildNodes[0].InnerText.ToInt(),
                        Name = liNode.ChildNodes[1].InnerText
                    }));
            }

            var actionSubstitutions = new List<ActionSubstitution>();

            var divteam1Actualsubs = ulNodes?.FirstOrDefault(x =>
                x.Attributes.Contains("class") && x.Attributes["class"].Value == "team1actualsubs");
            if (divteam1Actualsubs != null)
            {
                actionSubstitutions.AddRange(divteam1Actualsubs.Descendants("li")
                    .Select(x => new ActionSubstitution
                        {
                            Min = x.ChildNodes[0].InnerText,
                            In = x.ChildNodes[1].ChildNodes[0].InnerText,
                            Out = x.ChildNodes[1].ChildNodes[2].InnerText,
                            Type = FormationType.Home
                    }));
            }

            var divteam2Actualsubs = ulNodes?.FirstOrDefault(x =>
                x.Attributes.Contains("class") && x.Attributes["class"].Value == "team2actualsubs");
            if (divteam2Actualsubs != null)
            {
                actionSubstitutions.AddRange(divteam2Actualsubs.Descendants("li")
                    .Select(x => new ActionSubstitution
                    {
                        Min = x.ChildNodes[0].InnerText,
                        In = x.ChildNodes[1].ChildNodes[0].InnerText,
                        Out = x.ChildNodes[1].ChildNodes[2].InnerText,
                        Type = FormationType.Away
                    }));
            }


            await _matchRepository.Add(match, clips, formations, substitutions, actionSubstitutions);

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
                            matchLink.ImageLink = aNode.ChildNodes[0].Attributes["data-img-url"].Value;
                        }
                        else
                        {
                            matchLink.Name = aNode.InnerHtml.Replace("&#8211;", "–").Replace("&#038;","&");
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
