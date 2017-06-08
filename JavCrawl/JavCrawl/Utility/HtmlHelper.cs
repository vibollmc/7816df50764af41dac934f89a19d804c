using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using JavCrawl.Models;
using HtmlAgilityPack;
using System.Net.Http;
using Newtonsoft.Json;

namespace javtool.Utility
{
    public class HtmlHelper
    {
        public async Task<List<movies>> GetMovies(string url)
        {
            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var results = JsonConvert.DeserializeObject<List<movies>>(json);

            return results;
        }
    }
}
