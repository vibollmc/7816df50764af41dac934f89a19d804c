using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Dal.Context;
using HighLights.Web.Entities;
using Microsoft.EntityFrameworkCore;

namespace HighLights.Web.Dal.Implement
{
    public class CrawlLinkRepository : ICrawlLinkRepository
    {
        private readonly HighLightsContext _dbContext;

        public CrawlLinkRepository(HighLightsContext dbContext)
        {
            _dbContext = dbContext;
        }

        public async Task<CrawlLink> GetActive()
        {
            return await _dbContext.CrawlLinks.FirstOrDefaultAsync(x => x.DeletedAt == null && !x.IsFinished || x.IsCircle);
        }

        public async Task<bool> UpdateFinished(int? id)
        {
            var crawlLink = await _dbContext.CrawlLinks.FirstOrDefaultAsync(x => x.Id == id);
            if (crawlLink == null) return true;

            if (crawlLink.Finished.HasValue) crawlLink.Finished++;
            else crawlLink.Finished = 1;

            crawlLink.IsFinished = (crawlLink.FromPage < crawlLink.ToPage && crawlLink.Finished == crawlLink.FromPage) || 
                (crawlLink.FromPage > crawlLink.ToPage && crawlLink.Finished == crawlLink.ToPage);

            crawlLink.UpdatedAt = DateTime.UtcNow;

            await _dbContext.SaveChangesAsync();

            return true;
        }
    }
}
