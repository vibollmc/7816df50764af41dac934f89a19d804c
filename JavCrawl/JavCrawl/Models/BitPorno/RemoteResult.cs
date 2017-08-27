using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models.BitPorno
{
    public class RemoteResult
    {
        public string id { get; set; }
        public string status { get; set; }
    }

    public class RemoteStatusResult
    {
        public string total_filesize { get; set; }
        public string transfer_filesize { get; set; }
        public string progress { get; set; }
        public string done { get; set; }
        public string object_code { get; set; }
    }
}
