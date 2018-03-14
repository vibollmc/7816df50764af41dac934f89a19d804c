using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Dal.Context;
using HighLights.Web.Entities;
using Microsoft.EntityFrameworkCore;

namespace HighLights.Web.Dal.Implement
{
    public class ImageServerRepository : IImageServerRepository
    {
        private readonly HighLightsContext _dbContext;

        public ImageServerRepository(HighLightsContext dbContext)
        {
            _dbContext = dbContext;
        }

        public async Task<ImageServer> GetActiveImageServer()
        {
            return await _dbContext.ImageServers.FirstOrDefaultAsync(x => x.DeletedAt == null);
        }
    }
}
