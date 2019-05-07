using Football.Show.Dal.Context;
using Football.Show.Entities;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Dal.Implement
{
    public class TagRepository : ITagRepository
    {
        private readonly MainDbContext _dbContext;
        private readonly string _domain;

        public TagRepository(LoadDbContext loadDbContext, IConfiguration configuration)
        {
            _dbContext = loadDbContext.DbContext;
            _domain = configuration["DomainHosting"];
        }
        public async Task<IEnumerable<Tag>> GetTags(string slug)
        {
            return await _dbContext.Tags
                .Where(x => x.Slug.Equals(slug, StringComparison.OrdinalIgnoreCase) && !x.DeletedAt.HasValue)
                .ToListAsync();
        }

        public async Task<IList<ViewModels.XmlModel>> GetXmlTags()
        {
            var date = DateTime.UtcNow;
            return await _dbContext.Tags.Where(x => !x.DeletedAt.HasValue)
                .Select(x => new ViewModels.XmlModel
                {
                    ChangeFreq = "daily",
                    LastMod = date,
                    Loc = $"http://{_domain}/tag/{x.Slug}",
                    Priority = 0.8
                })
                .Distinct()
                .ToListAsync();
        }
    }
}
