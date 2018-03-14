using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using HighLights.Web.Dal.Context;
using HighLights.Web.Utilities.Context;
using HighLights.Web.Utilities.Model;
using HtmlAgilityPack;

namespace HighLights.Web.Utilities.Implement
{
    public class Crawler : ICrawler
    {
        private readonly ICrawlLinkRepository _crawlLinkRepository;

        public Crawler(ICrawlLinkRepository crawlLinkRepository)
        {
            _crawlLinkRepository = crawlLinkRepository;
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
            }
            catch (Exception ex)
            {
                
            }

            return;
        }

        private async Task<IEnumerable<MatchLink>> GetMatchLinks(string url)
        {

            var htmlWeb = new HtmlWeb();
            var htmlDoc = await htmlWeb.LoadFromWebAsync(url);

            return null;
        }

        public async Task Run()
        {
            await CrawlData();
        }
    }
}
