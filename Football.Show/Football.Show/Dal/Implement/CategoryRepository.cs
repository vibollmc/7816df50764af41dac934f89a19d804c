using Football.Show.Dal.Context;
using Football.Show.Entities;
using Microsoft.EntityFrameworkCore;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Dal.Implement
{
    public class CategoryRepository : ICategoryRepository
    {
        public readonly MainDbContext _dbContext;
        public CategoryRepository(LoadDbContext loadDbContext)
        {
            _dbContext = loadDbContext.DbContext;
        }
        public async Task<IEnumerable<Category>> GetCategories()
        {
            return await _dbContext.Categories
                .Where(x => !x.DeletedAt.HasValue).OrderBy(x => x.Name).ToListAsync();
        }

        public async Task<Category> GetCategory(string slug)
        {
            return await _dbContext.Categories
                .FirstOrDefaultAsync(x => x.Slug.Equals(slug, StringComparison.OrdinalIgnoreCase));
        }

        public async Task<IEnumerable<Category>> GetMenuCategories()
        {
            return await _dbContext.Categories
                .Where(x => x.IsMenu && !x.DeletedAt.HasValue).OrderBy(x => x.Name).ToListAsync();
        }
    }
}
