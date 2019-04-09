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

        Task<IEnumerable<ViewModels.Match>> GetMatchs(int page);

        Task<int> GetTotalPage();

        Task<ViewModels.MatchDetail> GetMatchDetail(string slug);
    }
}
