using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models.Openload
{
    public class RemoteStatusResult
    {
        public int id { get; set; }
        public string remoteurl { get; set; }
        public string status { get; set; }
        public string bytes_loaded { get; set; }
        public string bytes_total { get; set; }
        public string folderid { get; set; }
        public string added { get; set; }
        public string last_update { get; set; }
        public string extid { get; set; }
        public string url { get; set; }
    }
}
