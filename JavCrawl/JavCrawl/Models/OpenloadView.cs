using JavCrawl.Models.DbEntity;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models
{
    public class OpenloadView
    {
        public IList<Episodes> Remoted { get; set; }
        public IList<Episodes> Error { get; set; }
        public IList<Episodes> Remoting { get; set; }
    }
}
