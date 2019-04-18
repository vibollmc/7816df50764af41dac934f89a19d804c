using Football.Show.Entities;
using Football.Show.Entities.Enum;
using Football.Show.Utilities;
using Football.Show.Utilities.Model;
using HtmlAgilityPack;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;

namespace Football.Show.Tests
{
    [TestClass]
    public class UnitTest1
    {
        private CookieCollection _cookies;
        private HtmlWeb _htmlWeb;

        [TestInitialize]
        public void Init()
        {
            _cookies = new CookieCollection();
            _htmlWeb = new HtmlWeb();

            InitialHttpWeb();
        }

        private void InitialHttpWeb()
        {
            _htmlWeb.OverrideEncoding = Encoding.Default;
            _htmlWeb.UseCookies = true;
            _htmlWeb.PreRequest += (request) =>
            {
                if (request.Method == "POST")
                {
                    string payload = request.Address.Query.Substring(1);
                    byte[] buff = Encoding.UTF8.GetBytes(payload.ToCharArray());
                    request.ContentLength = buff.Length;
                    request.ContentType = "application/x-www-form-urlencoded";
                    System.IO.Stream reqStream = request.GetRequestStream();
                    reqStream.Write(buff, 0, buff.Length);
                }

                request.CookieContainer.Add(_cookies);

                return true;
            };

            _htmlWeb.PostResponse += (request, response) =>
            {
                if (request.CookieContainer.Count > 0 || response.Cookies.Count > 0)
                {
                    _cookies.Add(response.Cookies);
                }
            };

        }

        private IList<Clip> GetMatchClip(string acpPost, string acpShortcode)
        {
            if (string.IsNullOrWhiteSpace(acpPost) || string.IsNullOrWhiteSpace(acpShortcode)) return null;

            var clips = new List<Clip>();

            for (var i = 1; i <= 7; i++)
            {
                var url = $"https://www.fullmatchesandshows.com/wp-admin/admin-ajax.php?acp_currpage={i}&acp_pid={acpPost}&acp_shortcode={acpShortcode}&action=pp_with_ajax";

                var doc = _htmlWeb.Load(url, "POST");

                var iframeNodes = doc.DocumentNode.Descendants("iframe");

                if (iframeNodes == null) continue;

                var link = iframeNodes.FirstOrDefault(x => x.Attributes.Contains("src") && 
                    !string.IsNullOrWhiteSpace(x.Attributes["src"].Value))?.Attributes["src"].Value;

                if (string.IsNullOrWhiteSpace(link)) continue;

                if (link.Contains("veuclips.com"))
                {
                    var uriLink = new Uri(link);

                    link = $"https://yfl.veuclips.com/embed/{uriLink.Segments[uriLink.Segments.Length - 1]}?autoplay=1&htmlplayer=1";
                }

                clips.Add(new Clip
                {
                    ClipType = (ClipType)i,
                    Url = link,
                    LinkType = LinkType.Embed,
                    Name = ((ClipType)i).ToTitle()
                });
            }

            return clips;
        }

        private bool SaveMatch(MatchLink matchLink)
        {
            var htmlDoc = _htmlWeb.Load(matchLink.Link);

            var articleNode = htmlDoc.DocumentNode.Descendants("article").FirstOrDefault();

            var iframeNode = articleNode?.Descendants("iframe");

            var ulNodes = articleNode?.Descendants("ul");

            var divNodes = articleNode?.Descendants("div");

            var inputNodes = articleNode?.Descendants("input");

            var divVcRow = divNodes?.FirstOrDefault(x =>
                x.Attributes.Contains("class") &&
                x.Attributes["class"].Value.Contains("vc_row") &&
                x.Attributes["class"].Value.Contains("wpb_row") &&
                x.Attributes["class"].Value.Contains("td-pb-row"));

            var divWpbWrapper =
                divVcRow?.Descendants("div")?.LastOrDefault(x => x.Attributes.Contains("class") && x.Attributes["class"].Value == "wpb_wrapper");

            if (divWpbWrapper == null || !iframeNode.Any()) return false;

            var acpPost = inputNodes?.FirstOrDefault(
                x => x.Attributes.Contains("id") &&
                    x.Attributes.Contains("value") &&
                    x.Attributes["id"].Value == "acp_post")?.Attributes["value"]?.Value;

            var acpShortcode = inputNodes?.FirstOrDefault(
                x => x.Attributes.Contains("id") &&
                    x.Attributes.Contains("value") &&
                    x.Attributes["id"].Value == "acp_shortcode")?.Attributes["value"]?.Value;

            var clips = GetMatchClip(acpPost, acpShortcode);

            if (clips == null || !clips.Any())
            {
                clips = iframeNode.Where(x =>
                    x.Attributes.Contains("data-lazy-src") &&
                    !x.Attributes["data-lazy-src"].Value.Contains("facebook.com"))
                .Select((x, i) => new Clip
                {
                    Url = x.Attributes["data-lazy-src"].Value,
                    ClipType = ClipType.PostMatch,
                    LinkType = LinkType.Embed,
                    Name = "Full show"
                }).ToList();
            }

            var match = new Match();
            match.Slug = matchLink.Slug;
            match.Title = matchLink.Name;

            //var ftpResult = await _ftpHelper.RemoteFiles(matchLink.ImageLink, match.Slug);

            //if (ftpResult != null)
            //{
            //    match.ImageName = ftpResult.FileName;
            //    match.ImageServerId = ftpResult.ServerId;
            //}

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
                    match.HomePersonScored += string.IsNullOrWhiteSpace(match.HomePersonScored)
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
                        match.AwayPersonScored += string.IsNullOrWhiteSpace(match.AwayPersonScored) ? node.InnerText : ("|" + node.InnerText);
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
                        Type = FormationType.Away,
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
                        Type = FormationType.Away,
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


            //await _matchRepository.Add(match, clips, formations, substitutions, actionSubstitutions);

            return true;
        }

        [TestMethod]
        public void TestMethod1()
        {
            var result = SaveMatch(new MatchLink
            {
                Date = "Apr 17, 2019",
                Link = "https://www.fullmatchesandshows.com/2019/04/17/uefa-champions-league-highlights-17-apr-2019/",
                ImageLink = "https://www.fullmatchesandshows.com/wp-content/uploads/2019/04/Manchester-City-vs-Tottenham-Hotspur.jpg",
                Name = "Manchester City vs Tottenham Hotspur – Highlights & Full Match"
            });

            Assert.IsTrue(result);
        }
    }
}
