using Football.Show.Dal.Context;
using Football.Show.Entities;
using Microsoft.EntityFrameworkCore;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Dal.Implement
{
    public class TagRepository : ITagRepository
    {
        private readonly MainDbContext _dbContext;
        public TagRepository(LoadDbContext loadDbContext)
        {
            _dbContext = loadDbContext.DbContext;
        }
        public async Task<Tag> GetTag(string slug)
        {
            return await _dbContext.Tags
                .FirstOrDefaultAsync(x => x.Slug.Equals(slug, StringComparison.OrdinalIgnoreCase));
        }

        public async Task<IEnumerable<Tag>> GetTags()
        {
            return await _dbContext.Tags.Where(x => !x.DeletedAt.HasValue).OrderBy(x => x.Name).ToListAsync();
        }
    }
}
