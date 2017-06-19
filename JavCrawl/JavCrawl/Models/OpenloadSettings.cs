using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models
{
    public class OpenloadSettings
    {
        public string ApiLogin { get; set; }
        public string ApiKey { get; set; }
        public string ApiLinkRemoteFile { get; set; }
        public string ApiLinkRemoteStatus { get; set; }
        public string ApiLinkRenameFile { get; set; }
    }
}
