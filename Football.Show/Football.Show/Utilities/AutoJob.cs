using Football.Show.Utilities.Context;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.Extensions.DependencyInjection;

namespace Football.Show.Utilities
{
    public class AutoJob
    {
        private readonly IServiceProvider _serviceProvider;
        public AutoJob(IServiceProvider serviceProvider)
        {
            _serviceProvider = serviceProvider;
        }

        public void ExecuteCrawlContent()
        {
            try
            {
                var crawler = _serviceProvider.GetService<ICrawler>();

                crawler.Run();
            }
            catch(Exception ex)
            {
                Console.WriteLine(ex.Message);
            }
        }
    }
}
