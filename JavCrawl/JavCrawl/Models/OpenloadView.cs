using JavCrawl.Models.DbEntity;
using System.Collections.Generic;

namespace JavCrawl.Models
{
    public class OpenloadView
    {
        public IList<Episodes> Remoted { get; set; }
        public IList<Episodes> Error { get; set; }
        public IList<Episodes> Remoting { get; set; }
    }
}
