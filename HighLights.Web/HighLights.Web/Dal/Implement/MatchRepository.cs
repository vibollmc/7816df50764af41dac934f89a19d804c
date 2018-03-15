using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Dal.Context;
using HighLights.Web.Entities;
using Microsoft.EntityFrameworkCore;

namespace HighLights.Web.Dal.Implement
{
    public class MatchRepository : IMatchRepository
    {
        private readonly HighLightsContext _dbContext;

        public MatchRepository(HighLightsContext dbContext)
        {
            _dbContext = dbContext;
        }

        public async Task<bool> CheckExsits(string slug)
        {
            return await _dbContext.Matchs.AnyAsync(x => x.Slug == slug);
        }

        public async Task<bool> Save(Match match)
        {
            if (match == null) return false;

            await _dbContext.Matchs.AddAsync(match);

            await _dbContext.SaveChangesAsync();

            return true;
        }
    }
}
