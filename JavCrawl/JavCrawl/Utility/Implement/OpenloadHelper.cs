using JavCrawl.Dal.Context;
using JavCrawl.Models;
using JavCrawl.Models.Openload;
using JavCrawl.Utility.Context;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Options;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Threading.Tasks;

namespace JavCrawl.Utility.Implement
{
    public class OpenloadHelper : IOpenloadHelper
    {
        private readonly OpenloadSettings _openloadSettings;
        private readonly IDbRepository _dbRepository;
        public OpenloadHelper(IOptions<OpenloadSettings> openloadSettings, IDbRepository dbRepository)
        {
            _openloadSettings = openloadSettings.Value;
            _dbRepository = dbRepository;
        }

        public async Task<bool> JobRemoteFile()
        {
            var epsNeedToCheckStatus = _dbRepository.GetEpisodeToCheckStatusRemote(HostingLink.Openload);

            if (epsNeedToCheckStatus != null)
            {
                var fileId = await RemoteFileStatus(epsNeedToCheckStatus.CustomerId.Value);
                var fileName = string.Format("javmile.com-{0}.mp4", epsNeedToCheckStatus.Id);

                if (!string.IsNullOrWhiteSpace(fileId))
                {
                    var result = await RenameFile(fileId, fileName);

                    var newLink = string.Format("https://openload.co/embed/{0}/{1}", fileId, fileName);

                    await _dbRepository.UpdateEpisodeWithNewLink(epsNeedToCheckStatus.Id, newLink);
                }
            }

            var epsNeedToRemote = _dbRepository.GetEpisodeToTranferOpenload(HostingLink.Openload);

            if (epsNeedToRemote != null)
            {
                var idRemote = await RemoteFile(epsNeedToRemote.FileName);

                await _dbRepository.UpdateEpisodeRemoteId(epsNeedToRemote.Id, idRemote);
            }

            return true;
        }

        public async Task<int> RemoteFile(string fileUrl)
        {
            var url = string.Format(_openloadSettings.ApiLinkRemoteFile, fileUrl);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return 0;

            var result = JsonConvert.DeserializeObject<OpenloadResult<RemoteResult>>(json);

            if (result == null || 
                result.status != ResultStatus.Success || 
                result.result == null || 
                string.IsNullOrWhiteSpace(result.result.id)) return 0;

            return int.Parse(result.result.id);
        }

        public async Task<string> RemoteFileStatus(int idRemote)
        {
            var url = string.Format(_openloadSettings.ApiLinkRemoteStatus, idRemote);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return null;

            var result = JsonConvert.DeserializeObject<OpenloadResult<dynamic>>(json);

            if (result == null ||
                result.status != ResultStatus.Success || result.result == null) return null;

            var jobject = result.result as Newtonsoft.Json.Linq.JObject;

            if (jobject.Count == 0) return null;

            var jproperty = jobject.First as Newtonsoft.Json.Linq.JProperty;

            var statusResults = JsonConvert.DeserializeObject<RemoteStatusResult>(jproperty.First.ToString());

            if (statusResults.status == "finished")
            {
                return statusResults.extid;
            }

            return null;
        }

        public async Task<bool> RenameFile(string fileId, string newName)
        {
            var url = string.Format(_openloadSettings.ApiLinkRenameFile, fileId, newName);

            var httpClient = new HttpClient();

            var json = await httpClient.GetStringAsync(url);

            if (json == null) return false;

            var result = JsonConvert.DeserializeObject<OpenloadResult<bool>>(json);

            if (result == null ||
                result.status != ResultStatus.Success) return false;

            return result.result;
        }
    }
}
