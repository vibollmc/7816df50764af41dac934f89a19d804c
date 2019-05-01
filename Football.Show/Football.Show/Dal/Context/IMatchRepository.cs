using System.Collections.Generic;
using System.Threading.Tasks;
using Football.Show.Entities;
using Football.Show.Utilities.Model;

namespace Football.Show.Dal.Context
{
    public interface IMatchRepository
    {
        Task<bool> CheckExsits(string slug);

        Task<bool> Add(Match match, IList<Clip> clips, IList<Formation> formations, IList<Substitution> substitutions, IList<ActionSubstitution> actionSubstitutions);

        Task<ViewModels.PagingResult> GetMatchs(int currentPage);
        Task<ViewModels.PagingResult> GetMatchsByCategory(string categorySlug, int currentPage);
        Task<ViewModels.PagingResult> GetMatchsByTag(string tagSlug, int currentPage);

        Task<ViewModels.MatchDetail> GetMatchDetail(string slug);

        Task<IEnumerable<ViewModels.Match>> GetMatchsNewest(int? id);
    }
}
