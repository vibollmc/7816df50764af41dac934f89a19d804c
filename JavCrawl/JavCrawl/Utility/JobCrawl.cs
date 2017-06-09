using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using FluentScheduler;
using JavCrawl.Dal.Context;

namespace JavCrawl.Utility
{
    public class JobCrawl : IJob
    {
        private readonly IDbRepository _dbRepository;
        public JobCrawl(IDbRepository dbRepository)
        {
            _dbRepository = dbRepository;
        }
        public void Execute()
        {
            try
            {
                var result = AsyncHelper.RunSync(() => _dbRepository.RunJobCrawl());
            }
            catch
            {

            }
        }
    }
}
