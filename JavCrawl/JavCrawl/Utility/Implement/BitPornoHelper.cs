using JavCrawl.Models;
using JavCrawl.Models.BitPorno;
using JavCrawl.Utility.Context;
using Microsoft.Extensions.Options;
using Newtonsoft.Json;
using System.Net.Http;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Implement
{
    public class BitPornoHelper : IBitPornoHelper
    {
        private readonly BitPornoSettings _bitPornoSettings;

        public BitPornoHelper(IOptions<BitPornoSettings> bitPornoSettings)
        {
            _bitPornoSettings = bitPornoSettings.Value;
        }

        public async Task<string> RemoteFile(string fileUrl)
        {
            var url = string.Format(_bitPornoSettings.ApiLinkRemoteFile, fileUrl);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var result = JsonConvert.DeserializeObject<BitPornoResult<RemoteResult>>(json);

            if (result == null ||
                result.status != BitPornoResultStatus.OK ||
                result.result == null ||
                string.IsNullOrWhiteSpace(result.result.id)) return null;

            return result.result.id;
        }

        public async Task<string> RemoteFileStatus(string idRemote)
        {
            var url = string.Format(_bitPornoSettings.ApiLinkRemoteStatus, idRemote);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var result = JsonConvert.DeserializeObject<BitPornoResult<RemoteStatusResult>>(json);

            if (result == null ||
                result.status != BitPornoResultStatus.OK || result.result == null) return null;

            if (result.result.progress != "100") return null;

            return string.Format(_bitPornoSettings.LinkEmbed, result.result.object_code);
        }
    }
}
