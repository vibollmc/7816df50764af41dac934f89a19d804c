using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using HighLights.Web.Entities;
using HighLights.Web.Utilities.Model;

namespace HighLights.Web.Dal.Context
{
    public interface IMatchRepository
    {
        Task<bool> CheckExsits(string slug);
        Task<bool> Add(Match match, IList<Clip> clips, IList<Formation> formations, IList<Substitution> substitutions, IList<ActionSubstitution> actionSubstitutions);
    }
}
