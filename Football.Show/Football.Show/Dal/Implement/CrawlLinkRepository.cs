using System;
using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Football.Show.Entities;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;

namespace Football.Show.Dal.Implement
{
    public class CrawlLinkRepository : ICrawlLinkRepository
    {
        private readonly MainDbContext _dbContext;

        public CrawlLinkRepository(LoadDbContext loadDbContext)
        {
            _dbContext = loadDbContext.DbContext;
        }

        public async Task<CrawlLink> GetActive()
        {
            return await _dbContext.CrawlLinks.FirstOrDefaultAsync(x => x.DeletedAt == null && (!x.IsFinished || x.IsCircle));
        }

        public async Task<bool> UpdateFinished(int? id)
        {
            var crawlLink = await _dbContext.CrawlLinks.FirstOrDefaultAsync(x => x.Id == id);
            if (crawlLink == null) return true;

            if (crawlLink.Finished.HasValue) crawlLink.Finished++;
            else crawlLink.Finished = 1;

            crawlLink.IsFinished = (crawlLink.FromPage < crawlLink.ToPage && crawlLink.Finished >= crawlLink.ToPage) || 
                (crawlLink.FromPage > crawlLink.ToPage && crawlLink.Finished >= crawlLink.FromPage);

            crawlLink.UpdatedAt = DateTime.UtcNow;

            await _dbContext.SaveChangesAsync();

            return true;
        }
    }
}
