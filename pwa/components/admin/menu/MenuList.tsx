import {FieldGuesser, ListGuesser} from "@api-platform/admin";

const MenuList = (props:{props:any})  => (
  <ListGuesser {...props}>
    <FieldGuesser source={"path"} />
    <FieldGuesser source={"isBottomMenu"} />
    <FieldGuesser source={"isLeftMenu"} />
    <FieldGuesser source={"isTopMenu"} />
    <FieldGuesser source={"name"} />

    <FieldGuesser source={"status"} />
    <FieldGuesser source={"type"} />
  </ListGuesser>
);

export default MenuList;
