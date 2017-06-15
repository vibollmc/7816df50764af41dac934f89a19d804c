using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models.Openload
{
    public class OpenloadResult<T>
    {
        public ResultStatus status { get; set; }
        public string msg { get; set; }
        public T result { get; set; }
    }

    public enum ResultStatus
    {
        Success = 200,
        BadRequest = 400,
        PermissionDenined = 403,
        FileNotFound = 404,
        Unavailable = 451,
        BandwidthExceeded = 509
    }
}
