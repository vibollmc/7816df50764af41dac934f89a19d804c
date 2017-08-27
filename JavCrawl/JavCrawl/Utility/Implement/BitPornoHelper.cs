using JavCrawl.Dal.Context;
using JavCrawl.Models;
using JavCrawl.Models.BitPorno;
using JavCrawl.Utility.Context;
using Microsoft.Extensions.Options;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Implement
{
    public class BitPornoHelper : IBitPornoHelper
    {
        private readonly BitPornoSettings _bitPornoSettings;
        private readonly IDbRepository _dbRepository;

        public BitPornoHelper(IOptions<BitPornoSettings> bitPornoSettings, IDbRepository dbRepository)
        {
            _bitPornoSettings = bitPornoSettings.Value;
            _dbRepository = dbRepository;
        }
        public async Task<bool> JobRemoteFile()
        {
            var epsNeedToCheckStatus = _dbRepository.GetEpisodeToCheckStatusRemote(HostingLink.BitPorno);

            if (epsNeedToCheckStatus != null)
            {
                var fileId = await RemoteFileStatus(epsNeedToCheckStatus.CustomerId.Value);

                if (!string.IsNullOrWhiteSpace(fileId))
                {
                    var newLink = string.Format("https://www.bitporno.com/embed/{0}", fileId);

                    await _dbRepository.UpdateEpisodeWithNewLink(epsNeedToCheckStatus.Id, newLink);
                }
            }

            var epsNeedToRemote = _dbRepository.GetEpisodeToTranferOpenload(HostingLink.BitPorno);

            if (epsNeedToRemote != null)
            {
                var idRemote = await RemoteFile(epsNeedToRemote.FileName);

                await _dbRepository.UpdateEpisodeRemoteId(epsNeedToRemote.Id, idRemote);
            }

            return true;
        }

        public async Task<int> RemoteFile(string fileUrl)
        {
            var url = string.Format(_bitPornoSettings.ApiLinkRemoteFile, fileUrl);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return 0;

            var result = JsonConvert.DeserializeObject<BitPornoResult<RemoteResult>>(json);

            if (result == null ||
                result.status != BitPornoResultStatus.OK ||
                result.result == null ||
                string.IsNullOrWhiteSpace(result.result.id)) return 0;

            return int.Parse(result.result.id);
        }

        public async Task<string> RemoteFileStatus(int idRemote)
        {
            var url = string.Format(_bitPornoSettings.ApiLinkRemoteStatus, idRemote);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var result = JsonConvert.DeserializeObject<BitPornoResult<RemoteStatusResult>>(json);

            if (result == null ||
                result.status != BitPornoResultStatus.OK || result.result == null) return null;

            if (result.result.progress != "100") return null;

            return result.result.object_code;
        }
    }
}
