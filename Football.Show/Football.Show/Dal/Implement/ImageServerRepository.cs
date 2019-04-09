using System.Threading.Tasks;
using Football.Show.Dal.Context;
using Football.Show.Entities;
using Microsoft.EntityFrameworkCore;

namespace Football.Show.Dal.Implement
{
    public class ImageServerRepository : IImageServerRepository
    {
        private readonly MainDbContext _dbContext;

        public ImageServerRepository(LoadDbContext loadDbContext)
        {
            _dbContext = loadDbContext.DbContext;
        }

        public async Task<ImageServer> GetActiveImageServer()
        {
            return await _dbContext.ImageServers.FirstOrDefaultAsync(x => !x.DeletedAt.HasValue);
        }
    }
}
