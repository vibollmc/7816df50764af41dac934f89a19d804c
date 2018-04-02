using System.Threading.Tasks;
using HighLights.Web.Entities;

namespace HighLights.Web.Dal.Context
{
    public interface ICrawlLinkRepository
    {
        Task<CrawlLink> GetActive();
        Task<bool> UpdateFinished(int? id);
    }
}
