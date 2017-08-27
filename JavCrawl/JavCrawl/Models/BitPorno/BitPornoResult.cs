using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Models.BitPorno
{
    public class BitPornoResult<T>
    {
        public BitPornoResultStatus status { get; set; }
        public string msg { get; set; }
        public T result { get; set; }
    }

    public enum BitPornoResultStatus
    {
        OK = 200,
        BadRequest = 400,
        PermissionDenied = 403,
        FileNotFound = 404,
        UnavailableForLegalReasons = 451
    }
}
