using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using FluentScheduler;

namespace JavCrawl.Utility
{
    public class JobRegistry:Registry
    {
        public JobRegistry()
        {
            Schedule<JobCrawl>().ToRunNow().AndEvery(1).Minutes();
        }
    }
}
